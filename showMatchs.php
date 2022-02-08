<?php
 include "header.php";
?>


<?php
if ($_SESSION["auth"] != true) {
  ?>
  <div class="container">
    <div class="row ">
      <div class="col-12 text-center mt-5">
        <h1><u><a class="text-black" href="login.php">Veuillez vous connecter pour consulter vos matchs  !</a></u></h1>
      </div>
    </div>
  </div>
  <?php
}else{

 ?>
<div class="container">
  <div class="row ">
    <div class="col-12 text-center mt-5">
      <h1><u>Choisissez un match !</u></h1>
    </div>
  </div>
</div>



    <table class="table table-striped text-center mt-4 mb-5" >
      <thead>
        <tr>
          <th scope="col">Equipe</th>
          <th scope="col">Jeux</th>
          <th scope="col">Date</th>
        </tr>
      </thead>
      <tbody >

        <?php

        $connection = connectDB();
        $queryPrepared = $connection->prepare("SELECT Team, name, game FROM ".PRE."TeamUser INNER JOIN ".PRE."Team ON Team = id_team where User=:id;");
        $queryPrepared->execute(["id"=>$_SESSION["info"]["id_user"]]);

        $results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);


        foreach ($results as $teams => $infoteam) {
          foreach ($infoteam as $key => $info) {
            if ($key == 'Team') {
              $queryPrepared = $connection->prepare("SELECT * from ".PRE."Matchs INNER JOIN ".PRE."TeamMatchs ON id_matchs=Matchs
      WHERE validationStatus=0 AND Team=:team;");
              $queryPrepared->execute(["team"=>$info]);

              $match = $queryPrepared->fetch(PDO::FETCH_ASSOC);

              if (!empty($match)) {

                //affiche sous forme de tableau les match en cours
                echo "<tr>";
                  echo " <th scope='row' style='vertical-align: middle;'  height='70em'> <a class='text-black' href='Endgame.php?firstTeam=".$results[$teams]["Team"]."'>".$results[$teams]["name"]."</a></th> ";
                  echo "<td style='vertical-align: middle;'> <a class='text-black' href='Endgame.php?firstTeam=".$results[$teams]["Team"]."'>".$results[$teams]["game"]."</a></td>";
                  echo "<td style='vertical-align: middle;'> <a class='text-black' href='Endgame.php?firstTeam=".$results[$teams]["Team"]."'>";
                  if (!empty($match["date"])) {
                    echo date('d-m-Y', strtotime($match["date"]));
                  }else {
                     echo " ";
                  }
                  echo  "</a></td>";
                echo "</tr>";



              }

            }
          }
        }
      }
         ?>

      </tbody>
    </table>


<?php

include "footer.php";
?>
