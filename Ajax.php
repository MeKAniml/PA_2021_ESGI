<?php
require "functions.php";
session_start();

    $connection = connectDB();

    if (isset($_GET['searchMembers'])) {

      $members = $_GET['searchMembers']."%";

      $queryPrepared = $connection->prepare("SELECT Id_user, Username, Email, Birthdate, isBan, isRef, superAdmin FROM ".PRE."User WHERE username LIKE :username or email LIKE :email or Id_user LIKE :id");
      $queryPrepared->execute(["username"=>$members, "email"=>$members, "id"=>$members]);
      $results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

      foreach ($results as $users => $infousers ) {
        foreach ($infousers as $cle => $info) {
          if ($cle == "Id_user") {
            echo "<th scope=row>".$info."</th>";
          } elseif ($cle == "Email" || $cle == "Username") {
            echo "<td>".$info."</td>";
          } elseif ($cle == "Birthdate") {
              if(empty($info)){
                echo "<td> N/A </td>";
              } else{
                echo "<td>".$info."</td>";
              }
          }elseif ($cle== "isBan") {
            if ($info==1){
            echo "<td>ban</td>";
          }else {
            echo"<td></td>";
          }
        }elseif ($cle== "isRef") {
            if ($info==1){
            echo "<td>arbitre</td>";
          }else {
            echo"<td></td>";
          }
        }elseif ($cle== "superAdmin") {
            if ($info==1){
            echo "<td>admin</td>";
          }else {
            echo"<td></td>";
          }
          }

        }
        foreach ($infousers as $cle => $info) {
          if ($cle == 'Id_user') {
            echo '<div class="dropdown">';

              echo '<td><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo '<img src="Images\settings.svg" width="20px"  id='.$info.'>';
              echo '</button>';
              echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                echo '<a onclick="changeStatus(1,'.$info.',1)" class="dropdown-item" src="">Bannir</a>';
                echo '<a onclick="changeStatus(2,'.$info.',1)" class="dropdown-item" href="#">Promouvoir Admin</a>';
                echo '<a onclick="changeStatus(3,'.$info.',1)" class="dropdown-item" href="#">Promouvoir Arbitre</a>';
                 echo '<a onclick="changeStatus(4,'.$info.',1)" class="dropdown-item" href="#">Débannir</a>';
                 echo '<a onclick="changeStatus(5,'.$info.',1)" class="dropdown-item" href="#">Retirer Admin</a>';
                 echo '<a onclick="changeStatus(6,'.$info.',1)" class="dropdown-item" href="#">Retirer Arbitre</a>';
              echo '</div>';

            echo '</div></td>';

          }
        }
        echo "</tr>";
      }

    }elseif (isset($_GET['searchTeams'])) {

      $teams = $_GET['searchTeams']."%";

      $queryPrepared = $connection->prepare("SELECT ".PRE."Team.id_team,".PRE."Team.name,".PRE."Team.description,".PRE."Team.isBan,".PRE."User.username FROM ".PRE."TeamUser INNER JOIN ".PRE."Team ON ".PRE."TeamUser.Team = ".PRE."Team.id_team INNER JOIN ".PRE."User ON ".PRE."TeamUser.User = ".PRE."User.id_user WHERE ".PRE."TeamUser.isCaptain=1 AND (username like :capname OR name like :name OR id_team like :id)");
      $queryPrepared->execute(["capname"=>$teams, "name"=>$teams, "id"=>$teams]);
      $results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

      foreach ($results as $teams => $infoteams ) {
        foreach ($infoteams as $cle => $info) {
          if ($cle == "id_team") {
            echo "<th scope=row>".$info."</th>";
          } elseif ($cle == "name") {
            echo "<td>".$info."</td>";
          } elseif ($cle == "description") {
              if(empty($info)){
                echo "<td> N/A </td>";
              } else{
                echo "<td>".$info."</td>";
              }
          }elseif ($cle== "isBan") {
            if ($info==1){
            echo "<td>ban</td>";
          }else {
            echo"<td></td>";
          }
        }elseif ($cle=="username") {
          echo"<td>".$info."</td>";
        }

        }
        foreach ($infoteams as $cle => $info) {
          if ($cle == 'id_team') {
            echo '<div class="dropdown">';

              echo '<td><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo '<img src="Images\settings.svg" width="20px"  id='.$info.'>';
              echo '</button>';
              echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                echo '<a onclick="changeStatus(1,'.$info.',2)" class="dropdown-item" src="">Bannir</a>';
                echo '<a onclick="changeStatus(2,'.$info.',2)" class="dropdown-item" href="#">débannir</a>';

              echo '</div>';

            echo '</div></td>';

          }
        }
        echo "</tr>";
      }

    }else if (isset($_GET['messages'])) {

      $messages = $_GET['messages']."%";


      $queryPrepared = $connection->prepare("SELECT * FROM Test_Message WHERE Id LIKE :id or Email LIKE :email or Username LIKE :username or Message=:message ORDER BY Id DESC;");
      $queryPrepared->execute(["id"=>$messages, "email"=>$messages, "username"=>$messages, "message"=>$messages]);
      $results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

      foreach ($results as $users => $infousers ) {
        foreach ($infousers as $cle => $info) {
          if ($cle == "Id") {
            echo "<th scope=row>".$info."</th>";
          } elseif ($cle == "Email" || $cle == "Username") {
            echo "<td>".$info."</td>";
          } elseif ($cle == "Message") {
              if(empty($info)){
                echo "<td> N/A </td>";
              } else{
                echo "<td>".$info."</td>";
              }
          }
        }
        echo "</tr>";
      }

    }elseif(isset($_GET['id'])&&isset($_GET['idTable'])){
    $type = intval($_GET['type']);
    $ind = intval($_GET['id']);
    $usr = intval($_GET['idTable']);
    $connection = connectDB();
    if ($type==1) {


    if ($ind==1) {

    $queryPrepared = $connection->prepare("UPDATE ".PRE."User SET isBan=1 where id_user=:id_user");
    $queryPrepared->execute(["id_user"=>$usr]);

    } elseif ($ind==2) {

      $queryPrepared = $connection->prepare("UPDATE ".PRE."User SET superAdmin=1 where id_user=:id_user");
      $queryPrepared->execute(["id_user"=>$usr]);

    }elseif ($ind==3) {


      $queryPrepared = $connection->prepare("UPDATE ".PRE."User SET isRef=1 WHERE id_user=:id_user");
      $queryPrepared->execute(["id_user"=>$usr]);

    }elseif ($ind==4) {

    $queryPrepared = $connection->prepare("UPDATE ".PRE."User SET isBan=0 where id_user=:id_user");
    $queryPrepared->execute(["id_user"=>$usr]);

  }elseif ($ind==5) {

    $queryPrepared = $connection->prepare("UPDATE ".PRE."User SET superAdmin=0 where id_user=:id_user");
    $queryPrepared->execute(["id_user"=>$usr]);

  }elseif ($ind==6) {

    $queryPrepared = $connection->prepare("UPDATE ".PRE."User SET isRef=0 where id_user=:id_user");
    $queryPrepared->execute(["id_user"=>$usr]);

    }
    }
  elseif ($type==2) {
    if ($ind==1) {
      $queryPrepared = $connection->prepare("UPDATE ".PRE."Team SET isBan=1 where id_team=:id_team");
      $queryPrepared->execute(["id_team"=>$usr]);
      echo "ban";
    }elseif ($ind==2) {
      echo "pas ban";
    $queryPrepared = $connection->prepare("UPDATE ".PRE."Team SET isBan=0 where id_team=:id_team");
    $queryPrepared->execute(["id_team"=>$usr]);

    }
  }
}elseif (isset($_GET["Team"]) && isset($_GET["Username"]) && isset($_GET["status"])) {

      $team = $_GET["Team"];
      $Username = $_GET["Username"];
      $status = $_GET["status"];






      if ($status == 1) {
        $queryPrepared = $connection->prepare("DELETE FROM ".PRE."TeamUser WHERE team=:id_team and User =
          (SELECT id_user FROM ".PRE."User INNER JOIN ".PRE."TeamUser ON ".PRE."User.id_user = ".PRE."TeamUser.User WHERE username = :user and Team = :id_team2);");
        $queryPrepared->execute(["id_team"=>$team, "user"=>$Username, "id_team2"=>$team]);

        $queryPrepared = $connection->prepare("SELECT ".PRE."TeamUser.isCaptain, username FROM ".PRE."User INNER JOIN ".PRE."TeamUser ON ".PRE."User.id_user = ".PRE."TeamUser.User WHERE Team=:id_team ;");
        $queryPrepared->execute(["id_team"=>$team]);
        $teamMembers = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);



        foreach ($teamMembers as $users => $infousers ) {
          foreach ($infousers as $cle => $info) {
          if ( $cle == "username") {
              echo '<td class="col">';
                echo '  <div class="input-group">';
                  echo '<p>';
                  if ($teamMembers[$users]['isCaptain'] == 1) {
                    echo '<img class="mr-2" src="Images\star.svg" width="20px">';
                  }
                  echo $info;
                  echo '</p>' ;

                  echo '<div class="dropdown">';
                    echo '<button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                      echo '<img src="Images\pen.svg" width="20px"  id='.$info.'>';
                    echo '</button>';
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                      ?>
                      <a class="dropdown-item" onclick="EditTeam('<?php echo $team; ?>','<?php echo $info; ?> ',1)">1</a>
                      <a class="dropdown-item" onclick="EditTeam('<?php echo $team; ?>','<?php echo $info; ?> ',2)">2</a>
                      <?php

                    echo '</div>';
                  echo '</div>';
                echo '</div>';
            echo '</td>';
            }
          }
          echo "</tr>";
        }

      } elseif ($status == 2) {

        $queryPrepared = $connection->prepare("UPDATE ".PRE."TeamUser SET isCaptain = 0 WHERE Team=:team AND isCaptain = 1;");
        $queryPrepared->execute(["team"=>$team]);

        $queryPrepared = $connection->prepare("UPDATE ".PRE."TeamUser SET isCaptain = 1 WHERE Team = :team AND User = (SELECT id_user from ".PRE."User where username=:name);");
        $queryPrepared->execute(["team"=>$team, "name"=>$Username]);


        $queryPrepared = $connection->prepare("SELECT ".PRE."TeamUser.isCaptain, username FROM ".PRE."User INNER JOIN ".PRE."TeamUser ON ".PRE."User.id_user = ".PRE."TeamUser.User WHERE Team=:id_team ;");
        $queryPrepared->execute(["id_team"=>$team]);
        $teamMembers = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

        $verif = 0;

        foreach ($teamMembers as $users => $infousers ) {
          foreach ($infousers as $cle => $info) {
          if ( $cle == "username") {
              echo '<td class="col">';
                echo '  <div class="input-group">';
                  echo '<p>';
                  if ($teamMembers[$users]['isCaptain'] == 1) {
                    echo '<img class="mr-2" src="Images\star.svg" width="20px">';
                  }
                  echo $info;
                  echo '</p>' ;
                  if ($verif == 1 && $info != $_SESSION["info"]["username"]) {
                  echo '<div class="dropdown">';
                    echo '<button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                      echo '<img src="Images\pen.svg" width="20px"  id='.$info.'>';
                    echo '</button>';
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                      ?>
                      <a class="dropdown-item" onclick="EditTeam('<?php echo $team; ?>','<?php echo $info; ?> ',1)">Exclure</a>
                      <a class="dropdown-item" onclick="EditTeam('<?php echo $team; ?>','<?php echo $info; ?> ',2)">Promouvoir Capitaine</a>
                      <?php
                    echo '</div>';
                  echo '</div>';
                }
                echo '</div>';
            echo '</td>';
            }
          }
          echo "</tr>";
        }
      }

  }elseif (isset($_GET["leaderboard"])) {

    $queryPrepared = $connection->prepare("INSERT INTO ".PRE."Memo (username, time) VALUES (:pseudo, :time)");
    $queryPrepared->execute(["pseudo"=>$_GET["pseudo"], "time"=>$_GET["time"]]);

    $queryPrepared = $connection->prepare("SELECT username, time FROM ".PRE."Memo ORDER BY time LIMIT 10;");
    $queryPrepared->execute();
    $leader = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

    foreach ($leader as $key=> $joueur) {

      echo '<tr>';
      echo   '<th scope="row">'.$leader[$key]["username"].'</th>';
      echo   '<td>'.$leader[$key]["time"].'</td>';
      echo '</tr>';

    }
  } elseif (isset($_GET["Boutton"]) && isset($_GET["Team"]) && isset($_GET["id_user"])) {

    $queryPrepared=$connection->prepare("SELECT isCaptain FROM ".PRE."TeamUser where User=:user and Team=:team;");
    $queryPrepared -> execute(["user"=>$_GET["id_user"], "team"=>$_GET["Team"]]);
    $isCaptaine = $queryPrepared->fetch(PDO::FETCH_ASSOC);

    $queryPrepared = $connection -> prepare("SELECT color from ".PRE."Team WHERE id_team=:id");
    $queryPrepared -> execute(["id"=>$_GET["Team"]]);
    $results = $queryPrepared -> fetch(PDO::FETCH_ASSOC);


    $color = $results['color'];
    $id_team = $_GET["Team"];


    if ($isCaptaine["isCaptain"] == 1) {
      echo '<div class="row">';
      echo '<div class="col-12 text-right mt-2">';
      echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Invité des membres</button>';
      echo '</div>';
      echo '</div>';
      echo '<div class="row">';
      echo '<div class="col-12 text-right mt-2">';
      echo '<a href="algo.php?id_team='.$id_team.'">' ;
      echo '<button type="button" class="btn btn-primary" style="background-color:'.$color.'; border-color: '.$color.'"> Chercher un match </button>';
      echo '</a>';
      echo "</div>";
      echo "</div>";
    } elseif ($isCaptaine["isCaptain"] == 0) {
      echo '<div class="row">';
      echo '<div class="col-12 text-right mt-2">';
      echo '<button type="button" class="btn btn-primary text-white" disabled >Invité des membres</button>';
      echo '</div>';
      echo '</div>';
      echo '<div class="row">';
      echo '<div class="col-12 text-right mt-2">';
      echo '<button type="button" class="btn btn-primary" style="background-color:'.$color.'; border-color: '.$color.'" disabled>Chercher un match</button>';
      echo "</div>";
      echo "</div>";
    }


  }elseif (isset($_GET["ladder"])) {

    $queryPrepared = $connection->prepare("SELECT name, game, elo from KEMPLAY_Team where game=:game AND isBan=0 ORDER by elo DESC LIMIT 5");
    $queryPrepared->execute(["game"=>$_GET["ladder"]]);
    $ladder = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

    ?>
    <table class="table table-striped text-center mt-4 mb-5" >
      <thead>
        <tr>
          <th scope="col">Nom</th>
          <th scope="col">Jeu</th>
          <th scope="col">elo</th>
        </tr>
      </thead>
      <tbody >

        <?php



        foreach ($ladder as $Teams => $infoTeam) {

          echo "<tr>";
          foreach ($infoTeam as $key => $value) {
            if ($key=='name') {
              $name = $value;
              echo " <th scope='row' style='vertical-align: middle;'  height='70em'>".$value."</th> ";
            }elseif ($key == 'elo' || $key == 'game') {
              echo "<td style='vertical-align: middle;'>".$value."</td>";
            }
          }
          echo "</tr>";

        }

         ?>

      </tbody>
    </table>
<?php

}else{
    header("Location: index.php");
  }
