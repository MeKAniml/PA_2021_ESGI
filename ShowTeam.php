<?php
 include "header.php";
?>


<?php
if ($_SESSION["auth"] != true) {
  ?>
  <div class="container">
    <div class="row ">
      <div class="col-12 text-center mt-5">
        <h1><u><a class="text-black" href="login.php">Veuillez vous connecter pour consulter vos équipes !</a></u></h1>
      </div>
    </div>
  </div>
  <?php
}else{

 ?>
<div class="container">
  <div class="row ">
    <div class="col-12 text-center mt-5">
      <h1><u>Choisissez une équipe !</u></h1>
      <a href="newTeam.php">
        <button type="button" class="btn mt-3 text-right btn-primary">Créer la vôtre !</button>
      </a>

    </div>

  </div>
</div>



    <table class="table table-striped text-center mt-4 mb-5" >
      <thead>
        <tr>
          <th scope="col">Nom</th>
          <th scope="col">Jeux</th>
          <th scope="col">elo</th>
        </tr>
      </thead>
      <tbody >

        <?php

        $connection = connectDB();
        $queryPrepared = $connection->prepare("SELECT name, game, elo FROM ".PRE."Team INNER JOIN ".PRE."TeamUser ON ".PRE."Team.id_team = ".PRE."TeamUser.Team INNER JOIN ".PRE."User ON ".PRE."User.id_user = ".PRE."TeamUser.User WHERE id_user=:id;");
        $queryPrepared->execute(["id"=>$_SESSION["info"]["id_user"]]);

        $results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

        foreach ($results as $Teams => $infoTeam) {

          echo "<tr>";
          foreach ($infoTeam as $key => $value) {
            if ($key=='name') {
              $name = $value;
              echo " <th scope='row' style='vertical-align: middle;'  height='70em'> <a class='text-black' href='EditTeam2.php?name=".$name."'>".$value."</a></th> ";
            }elseif ($key == 'game' || $key == 'elo') {
              echo "<td style='vertical-align: middle;'> <a class='text-black' href='EditTeam2.php?name=".$name."'>".$value."</a></td>";
            }
          }
          echo "</tr>";

        }

         ?>

      </tbody>
    </table>


<?php
}
include "footer.php";
?>
