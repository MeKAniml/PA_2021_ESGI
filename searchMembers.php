<?php
require "functions.php";
session_start();

    $connection = connectDB();

    if (isset($_GET['searchMembers'])) {

      $members = $_GET['searchMembers']."%";

      $queryPrepared = $connection->prepare("SELECT  Username FROM ".PRE."User WHERE username LIKE :username ");
      $queryPrepared->execute(["username"=>$members]);
      $results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

      foreach ($results as $users => $infousers ) {
        foreach ($infousers as $cle => $info) {
        if ( $cle == "Username") {
            echo '<td class="col-8">';
              echo '<label class="sr-only" for="inlineFormInputGroupUsername" name="id_user">Username</label>';
              echo '<label class="sr-only" for="inlineFormInputGroupUsername" name="id_user">Username</label>';
            echo '  <div class="input-group">';
              echo '<div class="input-group-prepend">';
                echo '<div class="input-group-text">#</div>';
              echo '</div>';
              echo '<input type="text" class="form-control formStylefooter" placeholder="Username" value='.$info.'>';
            echo '</div>';
          echo '</td>';
          echo '<td>';
            echo '<div class=" col-4 form-check">';
              echo  '<input class="form-check-input" type="checkbox" name="option[]"  value='.$info.'>';
              echo  '<label class="form-check-label"></label>';
              echo '</div>';
            echo '</td>';
            }
          }
          echo "</tr>";
        }
      } else {
        header("Location: index.php");
      }
