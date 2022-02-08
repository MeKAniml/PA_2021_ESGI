<?php  include "header.php";

if ( $_SESSION["auth"]!=true || !isset($_GET["firstTeam"])){
  header('Location:index.php');
  exit;
}

$firstTeam = $_GET["firstTeam"];
$_SESSION['team']['id_team']=$firstTeam;


$connection = connectDB();

//Regarde si l'utilisateur est un capitaine d'équipe
$queryPrepared=$connection->prepare("SELECT isCaptain FROM ".PRE."TeamUser where User=:user and Team=:team;");
$queryPrepared -> execute(["user"=>$_SESSION["info"]["id_user"], "team"=>$firstTeam]);
$isCaptaine = $queryPrepared->fetch(PDO::FETCH_ASSOC);


//selectionne l'id des team qui participe au dernier match qu'effectue $_GET["firstTeam"]
$queryPrepared = $connection -> prepare("SELECT Team FROM ".PRE."TeamMatchs WHERE Matchs=(SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
    WHERE validationStatus=0 AND Team=:team);");
$queryPrepared -> execute(["team"=>$firstTeam]);
$results = $queryPrepared -> fetchALL(PDO::FETCH_ASSOC);


//SELECT toutes les infos de la team 1 et 2
$queryPrepared = $connection -> prepare("SELECT * FROM ".PRE."Team WHERE id_team=:id1 OR id_team=:id2;");
$queryPrepared -> execute(["id1"=>$results[0]["Team"], "id2"=>$results[1]["Team"]]);
$showteam = $queryPrepared -> fetchALL(PDO::FETCH_ASSOC);


//SELECT toutes les infos du matchs en cours
$queryPrepared = $connection -> prepare("SELECT * from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
  WHERE validationStatus=0 AND Team=:id");
$queryPrepared -> execute(["id"=>$results[0]["Team"]]);
$showmatch = $queryPrepared -> fetch(PDO::FETCH_ASSOC);
$_SESSION['team']['id_matchs']=$showmatch['id_matchs'];

if (empty($showmatch)) {
  header("Location: showMatchs.php");
}



//ajout l'honor en db dans TeamMatchs
if (!empty($_POST["honor"]) && ($_POST["honor"]=="GG" || $_POST["honor"]=="Fair" || $_POST["honor"]=="Friendly") ) {

  $queryPrepared = $connection->prepare("UPDATE ".PRE."TeamMatchs SET Honor=:honor WHERE Team=:team and Matchs=:match;");
  $queryPrepared->execute(["honor"=>$_POST["honor"], "team"=>$firstTeam, "match"=>$showmatch["id_matchs"]]);


}
//--------------------


if (!empty($_POST["win"])) {

  if (empty($showmatch["winner"])) {


    //inserer le premier gagnant en db
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET winner=:win WHERE id_matchs=(SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["win"=>$_POST["win"], "id"=>$firstTeam]);
    //------------

    //informe l'utilisateur que un des deux gagnant à été séléctionné
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET alert = 5  WHERE id_matchs = (SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //------------

    header("Refresh:0");


  } elseif ($showmatch["winner"] == $_POST["win"]) {


    //ajoute 20 d'elo au gagnant et retire 15 d'elo au perdant
    if ($showmatch["winner"] == $showteam[0]["name"]) {

      $queryPrepared = $connection->prepare("Update ".PRE."Team SET elo=:elo WHERE id_team=:team");
      $queryPrepared->execute(["elo"=>$showteam[0]["elo"]+20, "team"=>$showteam[0]["id_team"]]);

      $queryPrepared = $connection->prepare("Update ".PRE."Team SET elo=:elo WHERE id_team=:team");
      $queryPrepared->execute(["elo"=>$showteam[1]["elo"]-15, "team"=>$showteam[1]["id_team"]]);


    } elseif ($showmatch["winner"] == $showteam[1]["name"]) {

      $queryPrepared = $connection->prepare("Update ".PRE."Team SET elo=:elo WHERE id_team=:team");
      $queryPrepared->execute(["elo"=>$showteam[1]["elo"]+20, "team"=>$showteam[1]["id_team"]]);

      $queryPrepared = $connection->prepare("Update ".PRE."Team SET elo=:elo WHERE id_team=:team");
      $queryPrepared->execute(["elo"=>$showteam[0]["elo"]-15, "team"=>$showteam[0]["id_team"]]);

    }
    //----------------------------

    //Valide le match en mettant validationStatus à 1
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET validationStatus=1 WHERE id_matchs=(SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //------------------------


    //---------HONOR section--------------

    //recup les honneur qu'a mis les deux team
    $queryGetHonor = $connection -> prepare("SELECT Honor FROM ".PRE."TeamMatchs WHERE Matchs=:match AND Team=:team;");
    $queryGetHonor -> execute(["match"=>$showmatch["id_matchs"], "team"=>$showteam[0]["id_team"]]);
    $infoHonor=$queryGetHonor->fetch(PDO::FETCH_ASSOC);
    //------------

    //fait +1 sur l'honneur mis pas l'équipe adverse
    if ($infoHonor['Honor']=='GG') {

      $queryInsertHonor = $connection -> prepare("UPDATE ".PRE."Team SET honorGG=honorGG+1 where id_team=:team");
      $queryInsertHonor -> execute(["team"=>$showteam[1]["id_team"]]);

    } elseif ($infoHonor['Honor']=='Fair') {

      $queryInsertHonor = $connection -> prepare("UPDATE ".PRE."Team SET honorFair=honorFair+1 where id_team=:team");
      $queryInsertHonor -> execute(["team"=>$showteam[1]["id_team"]]);

    } elseif ($infoHonor['Honor']=='Friendly') {

      $queryInsertHonor = $connection -> prepare("UPDATE ".PRE."Team SET honorFriendly=honorFriendly+1 where id_team=:team");
      $queryInsertHonor -> execute(["team"=>$showteam[1]["id_team"]]);

    }
    //----------

    //fait la même mais pour l'autre équipes
    $queryGetHonor -> execute(["match"=>$showmatch["id_matchs"], "team"=>$showteam[1]["id_team"]]);
    $infoHonor=$queryGetHonor->fetch(PDO::FETCH_ASSOC);


    if ($infoHonor['Honor']=='GG') {

      $queryInsertHonor = $connection -> prepare("UPDATE ".PRE."Team SET honorGG=honorGG+1 where id_team=:team");
      $queryInsertHonor -> execute(["team"=>$showteam[0]["id_team"]]);

    } elseif ($infoHonor['Honor']=='Fair') {

      $queryInsertHonor = $connection -> prepare("UPDATE ".PRE."Team SET honorFair=honorFair+1 where id_team=:team");
      $queryInsertHonor -> execute(["team"=>$showteam[0]["id_team"]]);

    } elseif ($infoHonor['Honor']=='Friendly') {

      $queryInsertHonor = $connection -> prepare("UPDATE ".PRE."Team SET honorFriendly=honorFriendly+1 where id_team=:team");
      $queryInsertHonor -> execute(["team"=>$showteam[0]["id_team"]]);

    }
    //----------
    //---------FIN--------------


    header("Location: showMatchs.php");



  } else {

    //informe l'utilisateur que les gagnants sont différents
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET alert = 4  WHERE id_matchs = (SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    //supprimer le winner enregistré en db
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET winner = null WHERE id_matchs=(SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    header("Refresh:0");

  }


} elseif (!empty($_POST["date"])) {

  if (empty($showmatch["date"])) {


    //entre la date choisi par les joueur dans la db si aucune date est rentré
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET date=:date WHERE id_matchs=(SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["date"=>$_POST["date"], "id"=>$firstTeam]);
    //-------------

    //informe l'utilisateur qu'il faut avoir les deux dates identique pour jouer
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET alert = 3  WHERE id_matchs = (SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    header("Refresh:0");


  } elseif ($showmatch["date"] == $_POST["date"]) {


    //indique que la partie est prête à démarrer
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET start = 1 WHERE id_matchs = (SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    //informe l'utilisateur que le match est programmé
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET alert = 2  WHERE id_matchs = (SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    header("Refresh:0");


  } else {

    //informe l'utilisateur que les deux date ne sont pas bonne
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET alert = 1  WHERE id_matchs = (SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    //supprimer la date enregistré en db
    $queryPrepared = $connection -> prepare("UPDATE ".PRE."Matchs SET date = null WHERE id_matchs=(SELECT id_matchs from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:id);");
    $queryPrepared -> execute(["id"=>$firstTeam]);
    //-------------

    header("Refresh:0");

  }

}

?>


<section>
  <div class="container">
    <div class="row mt-5 mb-4">
      <div class="col-5 text-right">
        <br />
        <h1><b><?php echo  $showteam[0]["name"];?></b></h1>
      </div>
      <div class="col-1 mr-4">
        <img src="Images\Versus.svg" width="100rem"/>
      </div>
      <div class="col-5">
        <br />
        <h1><b><?php echo  $showteam[1]["name"];?></b></h1>
      </div>
    </div>

    <?php
    if ($showmatch["alert"] == 1) {
      echo '<div class="alert alert-warning" role="alert">';
      echo '<h4 class="alert-heading">Erreur</h4>';
      echo "Vous n'êtes pas d'accord sur la date.";
      echo '</div>';
    } elseif ($showmatch["alert"] == 2) {
      echo '<div class="alert alert-success" role="alert">';
      echo '<h4 class="alert-heading">Super !</h4>';
      echo "Le match est programmé pour le ".date('d-m-Y', strtotime($showmatch["date"]));
      echo '</div>';
    } elseif ($showmatch["alert"] == 3) {
      echo '<div class="alert alert-success" role="alert">';
      echo '<h4 class="alert-heading">Date enregistrée </h4>';
      echo "Si les deux équipes séléctionne la même date, la partie pourra commencer.";
      echo '</div>';
    } elseif ($showmatch["alert"] == 4) {
      echo '<div class="alert alert-danger" role="alert">';
      echo '<h4 class="alert-heading">Erreur</h4>';
      echo "Les gagnants saisis sont différents";
      echo '</div>';
    } elseif ($showmatch["alert"] == 5) {
      echo '<div class="alert alert-success" role="alert">';
      echo '<h4 class="alert-heading">C\'est bientôt fini</h4>';
      echo "Un gagnant sur deux à été saisis";
      echo '</div>';
    }
    ?>

    <div class="row">



      <!--DEBUT PREMIERE MOITIE-->
      <div class="col-6  text-center">
        <!--DEBUT DE LA DATE-->
        <div class="col-10 mt-4  ">
          <div class="tableprog p-3 " style="max-height: 10rem;" >
            <?php
            if ($showmatch["start"]==1 && $isCaptaine["isCaptain"]==1) {
              echo '<h6 class="my-2 font-weight-bold">Programmer votre partie : </h6>';
              echo '<input class="form-control my-2" type="text" disabled value="';
              echo date("d-m-Y", strtotime($showmatch["date"]));
              echo '">';
              echo '<div class="text-right">';
              echo '<button  type="submit" class="btn btn-secondary text-right" disabled>Validé</button>';
              echo '</div>';
            } elseif ($showmatch["start"]==0 && $isCaptaine["isCaptain"]==1){
              echo  '<form method="POST">';
              echo '<h6 class="my-2 font-weight-bold">Programmer votre partie : </h6>';
              echo '<input class="form-control my-2" id="date" type="date" datetime="YYYY-MM-DD HH:mm:ss" name="date" min="2021-04-10 08:30" required>';
              echo '<div class="text-right">';
              echo '<button  type="submit" class="btn btn-primary text-right">Valider</button>';
              echo '</div>';
              echo '</form>';
            }elseif ($isCaptaine["isCaptain"]==0) {
              echo '<h6 class="my-2 font-weight-bold">Programmer votre partie : </h6>';
              echo '<input class="form-control my-2" type="text" disabled value="';
              echo 'Vous n\'étes pas capitaine';
              echo '">';
              echo '<div class="text-right">';
              echo '<button  type="submit" class="btn btn-secondary text-right" disabled>Validé</button>';
              echo '</div>';
            }

            ?>
          </div>
        </div>
        <!-- FIN DE LA DATE -->


        <!--DEBUT HONOR-->

        <div class="col-9 mt-5">
          <?php
          if ($isCaptaine["isCaptain"]==0) {
            echo    '<input type="radio" name="honor" value="GG" disabled>';
            echo    '<img width="110"  src="Images\GG.svg" />';
            echo    '<input type="radio" name="honor" value="Fair" disabled>';
            echo    '<img width="110"  src="Images\Fairplay.svg" />';
            echo    '<input type="radio" name="honor" value="Friendly" disabled>';
            echo    '<img width="110"  src="Images\Amical.svg" />';
            echo    '<p>';
            echo      '<small>Vous pouvez changer l\'honneur donné jusqu\'à ce que le match se termine.</small>';
            echo    '</p>';
            echo    '<div class="text-right mt-4">';
            echo      '<button  type="submit" class="btn btn-secondary text-right" disabled>Valider</button>';
            echo    '</div>';
          } elseif ($isCaptaine["isCaptain"]==1) {
            echo  '<form method="POST">';
            echo    '<input type="radio" name="honor" value="GG">';
            echo    '<img width="110"  src="Images\GG.svg" />';
            echo    '<input type="radio" name="honor" value="Fair">';
            echo    '<img width="110"  src="Images\Fairplay.svg" />';
            echo    '<input type="radio" name="honor" value="Friendly">';
            echo    '<img width="110"  src="Images\Amical.svg" />';
            echo    '<p>';
            echo      '<small>Vous pouvez changer l\'honneur donné jusqu\'à ce que le match se termine.</small>';
            echo    '</p>';
            echo    '<div class="text-right mt-4">';
            echo      '<button class="btn btn-primary"> Valider </button>';
            echo    '</div>';
            echo  '</form>';
          }

          ?>
        </div>

        <!--FIN HONOR-->

        <div class="col-10 mt-5">
          <div class="bordertable shadow-sm p-4 j">
            <div class="row">
              <div class="offset-1 col-md-10">

                <?php
                //Desactive ou non le module WHO WON
                if ($showmatch["start"]==0 && $isCaptaine["isCaptain"]==1) {
                  echo "<p><b>WHO WON (WHO'S NEXT)</b></p>";
                  echo '<form method="post">';
                  echo '<select class="custom-select" disabled id="inputGroupSelect01" name="win" value=""     required>';
                  echo   '<option selected>Choisissez...</option>';
                  echo   '<option value="<?php echo $showteam[0]["name"]; ?>"><?php echo $showteam[0]["name"]; ?></option>';
                  echo   '<option value="<?php echo $showteam[1]["name"]; ?>"><?php echo $showteam[1]["name"]; ?></option>';
                  echo '</select>';
                  echo '<div class="text-right mt-4">';
                  echo   '<button class="btn btn-primary" disabled> Valider </button>';
                  echo '</div></form>';
                }elseif ($showmatch["start"]==1 && $isCaptaine["isCaptain"]==1) {
                  echo "<p><b>WHO WON (WHO'S NEXT)</b></p>";
                  echo '<form method="post">';
                  echo '<select class="custom-select" id="inputGroupSelect01" name="win" value=""     required>';
                  echo   '<option selected>Choisissez...</option>';
                  echo   '<option value="'.$showteam[0]["name"].'">'.$showteam[0]["name"].'</option>';
                  echo   '<option value="'.$showteam[1]["name"].'">'.$showteam[1]["name"].'</option>';
                  echo '</select>';
                  echo '<div class="text-right mt-4">';
                  echo   '<button class="btn btn-primary"> Valider </button>';
                  echo '</div></form>';
                } elseif ($isCaptaine["isCaptain"]==0) {
                  echo "<p><b>WHO WON (WHO'S NEXT)</b></p>";
                  echo '<select class="custom-select" disabled required>';
                  echo   '<option selected>Vous n\'étes pas capitaine</option>';
                  echo '</select>';
                  echo '<div class="text-right mt-4">';
                  echo   '<button class="btn btn-secondary" disabled> Valider </button>';
                  echo '</div>';
                }

                ?>

              </div>
            </div>
          </div>
        </div>


      </div>
      <!--FIN MOITIER PAGE-->

      <div class="col-6 ">

        <!--DEBUT CHAT-->
        <div class="boxBack mt-4 overflow-auto shadow border scrollable" style="max-height: 100%;">
          <style>.messages {
            width: 100%;
            max-height: 55vh;
            overflow-y: scroll;
            scrollbar-color: rebeccapurple green;
            scrollbar-width: thin;
            }</style>
            <div class="messages text-left">

            </div>
            <div class="userInputs ">
              <form onsubmit="return postMessage(event)">
                <input type="hidden" class="form-control" name="author" id="author" placeholder="<?php echo $_SESSION['info']['username']?>" disabled>
                <input type="text"  class="form-control mt-2" id="content" name="content" placeholder="Ecris ton message !" required="required">
                <button type="submit" class="btn btn-primary mt-2">Envoyez !</button>
              </form>
            </div>
            <script src="CSS/bootstrap-4.6.0-dist/js/messages.js"></script>
          </div>
          <!--FIN CHAT-->

        </div>

      </div>
    </div>
  </section>

<script src="CSS\bootstrap-4.6.0-dist\js\Prod.js" charset="utf-8"></script>
<?php include 'footer.php'; ?>
