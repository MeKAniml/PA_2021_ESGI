<?php
session_start();
require "functions.php";


if ($_GET["id_team"]) {

  $id_team = $_GET["id_team"];

  $_SESSION["noteam"] = [];
  $_SESSION["teamvs"] = [];
  $_SESSION["ingame"] = [];

  //vérifie si l'équipe a deja un match
  $connection = connectDB();
  $queryckeck = $connection->prepare("SELECT count(*) AS 'check' from KEMPLAY_TeamMatchs INNER JOIN KEMPLAY_Matchs on KEMPLAY_TeamMatchs.Matchs = KEMPLAY_Matchs.id_matchs Where Team=:team and validationStatus=0;");
  $queryckeck->execute(["team"=>$id_team]);
  $check = $queryckeck->fetch(PDO::FETCH_ASSOC);


  //recup les infos de la team qui recherche
  $connection = connectDB();
  $queryPrepared = $connection->prepare("SELECT * FROM ".PRE."Team WHERE id_team=:team");
  $queryPrepared->execute(["team"=>$id_team]);
  $thisTeam = $queryPrepared->fetch(PDO::FETCH_ASSOC);

  if ($check["check"] == 0) {


    //recup la liste des membres de l'équipe qui recherche
    $queryPrepared = $connection->prepare("SELECT User FROM ".PRE."TeamUser WHERE Team=:team;");
    $queryPrepared->execute(["team"=>$id_team]);
    $verifteam = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

    //recup une liste des équipes compatible
    $queryPrepared = $connection->prepare("SELECT id_team, name, elo, game FROM ".PRE."Team WHERE searchStatus=1 AND isBan=0 AND id_team!=:team AND (elo<=:elomax and elo>=:elomin) AND game=:game ORDER BY RAND();");
    $queryPrepared->execute(["team"=>$id_team, "elomax"=>$thisTeam["elo"]+100, "elomin"=>$thisTeam["elo"]-100, "game"=>$thisTeam["game"]]);
    $teamvs = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);



    if( $queryPrepared->rowCount() == 0 ){

      $queryPrepared = $connection->prepare("UPDATE ".PRE."Team SET searchStatus = 1 WHERE id_team=:team");
      $queryPrepared->execute(["team"=>$id_team]);

      $_SESSION["noteam"] = 1;
      header("Location: EditTeam2.php?name=".$thisTeam["name"]);

    } else {

      //Premier parcours pour avoir les membres des équipes potentielle
      foreach ($teamvs as $teams => $infoteam) {
        foreach ($infoteam as $key => $value) {

          if ($key == "id_team") {
            $queryPrepared = $connection->prepare("SELECT User FROM ".PRE."TeamUser WHERE Team=:team;");
            $queryPrepared->execute(["team"=>$value]);
            $tryteam = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);  //membre de l'équipe adverse

            //Deuxieme parcours pour voir si l'un des membres de l'équipe appartient déjà à l'équipe selectionné
            $verif = 1;
            //Parcours les membres de la team qui est en tain de rechercher
            foreach ($verifteam as  $verifinfouser) {
              foreach ($verifinfouser as $verifid) {

                //Parcours les membres de la team potentielle
                foreach ($tryteam as $infouser) {
                  foreach ($infouser as $id) {
                    //si l'id des joueurs est identique c'est qu'il est dans les deux équipes et l'équipe sera donc refusé
                    if ($verifid == $id) {
                      $verif = 0;
                      //suppression de l'équipe potentielle car meme memebre dans les deux équipes
                      unset($teamvs[$teams]);
                      break 4;
                    }
                  }
                }
              }
            }

            //affiche l'équipe s'il n'y a pas de pb
            if ($verif == 1) {

              //-----Créer un match et récupére l'id du match
              $querymatch = $connection->prepare("INSERT INTO ".PRE."Matchs (id_matchs) VALUES (:match);");

              $queryid = $connection->prepare("SELECT id_matchs FROM ".PRE."Matchs ORDER BY id_matchs DESC;");
              $queryid->execute();
              $lastmatch = $queryid->fetch(PDO::FETCH_ASSOC);

              $id_matchs = $lastmatch["id_matchs"]+1;

              $querymatch->execute(["match"=>$id_matchs]);
              //-----

              //Relie les 2 equipes au match créé
              for ($i=0; $i < 2 ; $i++) {
                $queryPrepared = $connection->prepare("INSERT INTO ".PRE."TeamMatchs (Team, Matchs) VALUES (:team, :match)");

                if ($i==0) {
                  $queryPrepared->execute(["team"=>$id_team, "match"=>$id_matchs]);
                }elseif ($i==1) {
                  $queryPrepared->execute(["team"=>$teamvs[$teams]["id_team"], "match"=>$id_matchs]);
                }
              }

              //-----

              $queryPrepared = $connection->prepare("UPDATE ".PRE."Team SET searchStatus = 0 WHERE id_team=:team OR id_team=:otherTeam");
              $queryPrepared->execute(["team"=>$id_team, "otherTeam"=>$teamvs[$teams]["id_team"]]);

              $_SESSION["teamvs"] = $teamvs[$teams];

              header("Location: EditTeam2.php?name=".$thisTeam["name"]);

              break 2;

            }
          }
        }
      }
    }



      if (empty($_SESSION["teamvs"])) {

        $queryPrepared = $connection->prepare("UPDATE ".PRE."Team SET searchStatus = 1 WHERE id_team=:team");
        $queryPrepared->execute(["team"=>$id_team]);

        $_SESSION["noteam"] = 1;
        header("Location: EditTeam2.php?name=".$thisTeam["name"]);

      }
  } else {
    $_SESSION["ingame"] = 1;
    header("Location: EditTeam2.php?name=".$thisTeam["name"]);
  }
} else {
  header("Location: index.php");
}





?>
