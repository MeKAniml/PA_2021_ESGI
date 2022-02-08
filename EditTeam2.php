<?php include "header.php";

if ( $_SESSION["auth"]!=true || !isset($_GET["name"])){
  header('Location:index.php');
  exit;
}

  $name = $_GET["name"];

//recup les infos de la team
 $connection = connectDB();
 $queryPrepared = $connection -> prepare("SELECT * from ".PRE."Team WHERE name=:name");
 $queryPrepared -> execute(["name"=>$name]);
 $results = $queryPrepared -> fetch(PDO::FETCH_ASSOC);

unset($_SESSION['team']['id_matchs']);
 $_SESSION["team"]=$results;

 $queryPrepared=$connection->prepare("SELECT isCaptain FROM ".PRE."TeamUser where User=:user and Team=:team;");
 $queryPrepared -> execute(["user"=>$_SESSION["info"]["id_user"], "team"=>$results["id_team"]]);
 $isCaptaine = $queryPrepared->fetch(PDO::FETCH_ASSOC);


?>




<hr class=" mt-5"/>
    <h1 class="text-center ">GESTIONNAIRE D'ÉQUIPE</h1>
<hr/>
<div class="container">
  <?php

  if (!empty($_SESSION["noteam"])) {
    echo '<div class="alert alert-warning" role="alert">';
    echo '<h4 class="alert-heading">Mise en fille d\'attente</h4>';
    echo '<p><b>Pas de chance</b>, il n\'y a pas de match en cours. Vous avez été mis en <b>liste d\'attente</b>.</p>';
    echo '</div>';
    $_SESSION["noteam"] = [];

  }elseif (!empty($_SESSION["teamvs"])) {
    echo '<div class="alert alert-success" role="alert">';
    echo '<h4 class="alert-heading">Match trouvé !!</h4>';
    echo 'Préparez-vous à affronter : <b>' .$_SESSION["teamvs"]["name"]. '</b>';
    echo '</div>';
    $_SESSION["teamvs"] = [];
  }elseif (!empty($_SESSION["ingame"])) {
    echo '<div class="alert alert-warning" role="alert">';
    echo '<h4 class="alert-heading">Vous êtes déjà en match.</h4>';
    echo 'Vous devez terminé votre match avant d\'en commencer un nouveau.';
    echo '</div>';
    $_SESSION["teamvs"] = [];
  }

  $queryHonor = $connection->prepare("
  SELECT
      CASE
          WHEN honorGG >= 1 AND honorGG >= honorFair AND honorGG >= honorFriendly THEN '<img width=150rem src=Images/GG.svg>'
          WHEN honorFair >= 1 AND honorFair >= honorGG AND honorFair >= honorFriendly THEN '<img width=150rem src=Images/Fairplay.svg>'
          WHEN honorFriendly >= 1 AND honorFriendly >= honorGG AND honorFriendly >= honorFair THEN '<img width=150rem src=Images/Amical.svg>'
          ELSE                                                               ' '
      END AS honor
  FROM ".PRE."Team
  WHERE id_team=:team;");
  $queryHonor->execute(["team"=>$results["id_team"]]);
  $honor = $queryHonor->fetch(PDO::FETCH_ASSOC);


   ?>
  <div class="row">
    <div class="col-5">
      <div class="navbar-brand">
       <?php echo $results["name"]; ?></a> <?php echo $honor['honor'];  ?>
      </div>
      <div class="form-group my-3 ">
        <a href="Historique.php" class="small" style="color:<?php echo $results['color'];?>"> Voir l'historique de cette équipe </a>
        <br>
        <label for="inputdescribe">Description</label>
        <textarea class="form-control formStyle" name="describe" rows="4" cols="5" placeholder="Ma description"><?php if (isset($results["description"])){echo $results["description"];}?></textarea>
      </div>


      <div class="row">
        <div class="col-6 ">
          <svg class="mt-3" width="150" height="200" viewBox="0 0 208 279" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d)">
            <path d="M187.968 115.436H20V209.184C20 215.437 23.6421 221.116 29.3245 223.725L90.6332 251.871C99.1087 255.762 108.86 255.762 117.335 251.871L178.644 223.725C184.326 221.116 187.968 215.437 187.968 209.184V115.436Z" fill="white"/>
            </g>
            <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="20" y="0" width="168" height="116">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M38.6639 1.10736C38.6639 11.3328 30.3081 19.6221 20.0008 19.6221C20.0005 19.6221 20.0003 19.6221 20 19.6221V115.436H187.968L187.968 19.6222C177.661 19.6222 169.305 11.3328 169.305 1.1074C169.305 0.95266 169.307 0.798363 169.311 0.644531H38.6582C38.662 0.798349 38.6639 0.952632 38.6639 1.10736Z" fill="white"/>
            </mask>
            <g mask="url(#mask0)">
            <path d="M187.968 0.644531H20V115.436H187.968V0.644531Z" fill="<?php echo $results['color']; ?>"/>
            </g>
            <defs>
            <filter id="filter0_d" x="0" y="99.4362" width="207.968" height="179.353" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
            <feOffset dy="4"/>
            <feGaussianBlur stdDeviation="10"/>
            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>
            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
            </filter>
            </defs>
          </svg>
        </div>
        <?php
        if ($results["game"] == "Super Smash Bros") {
          echo "<div class='col-6 '>";
          echo "<img class=' mt-2' width='160' src='Images/SSB.png' alt='logo Super Smash Bros'/>";
          echo "</div>";
        }elseif ($results["game"] == "Rocket League") {
          echo "<div class='col-6'>";
          echo "<img class=' mt-2' width='160' src='Images/rocketLeagueLogo.png' alt='logo Super Smash Bros'/>";
          echo "</div>";
        }elseif ($results["game"] == "League Of Legends") {
          echo "<div class='col-6'>";
          echo "<img class=' mt-2' width='160' src='Images/leagueoflegends.png' alt='logo Super Smash Bros'/>";
          echo "</div>";
        }

        ?>
      </div>


    </div>
    <div class="offset-2 col-1 mt-3">
      <img class="ml-4" src="Images/athlete.svg" width="50rem"/>
    </div>
    <div class=" col-4 mt-3">
      <p class="mt-3"><b>Membres de l'équipe :</b></p>

      <table class="table-borderless align-items-center">
        <form method="post">
          <tbody id="selectMembers">
            <?php
              $connection = connectDB();
              $queryPrepared = $connection->prepare("SELECT ".PRE."TeamUser.isCaptain, username FROM ".PRE."User INNER JOIN ".PRE."TeamUser ON ".PRE."User.id_user = ".PRE."TeamUser.User WHERE Team=:id_team ;");
              $queryPrepared->execute(["id_team"=>$results["id_team"]]);
              $teamMembers = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

              $color = $results['color'];
              $id_team = $results['id_team'];
              ?>

              <?php

              // Verifier si l'utilisateur actuel qui consulte la page est un capitaine ou pas (différent droit en fonction)
              $verif = 0;
              foreach ($teamMembers as $users => $infousers) {
                foreach ($infousers as $cle => $info) {
                  if ($teamMembers[$users]['isCaptain'] == 1 && $teamMembers[$users]['username'] == $_SESSION["info"]["username"]) {
                    $verif = 1;
                  }
                }
              }


              foreach ($teamMembers as $users => $infousers ) {
                foreach ($infousers as $cle => $info) {
                if ( $cle == "username") {
                    echo '<td class="col">';
                      echo '<div class="input-group">';

                        echo '<p>';
                        //Afiiche une étoile si capitaine
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
                              <a class="dropdown-item" onclick="EditTeam('<?php echo $results["id_team"]; ?>','<?php echo $info; ?> ',1,'<?php echo $_SESSION["info"]["id_user"]; ?>')">Exclure</a>
                              <a class="dropdown-item" onclick="EditTeam('<?php echo $results["id_team"]; ?>','<?php echo $info; ?> ',2,'<?php echo $_SESSION["info"]["id_user"]; ?>')">Promouvoir Capitaine</a>


                              <?php
                        }

                      echo '</div>';
                  echo '</td>';
                  }
                }
                echo "</tr>";
              }
              ?>
          </tbody>
        </form>
      </table>

    </div>
  </div>


  <!-- JE SUIS UN PUTAIN DE CHAT MIAOUUUUUUUUUUUU -->
  <section class="chat mt-5">
    <style>.messages {
      width: 100%;
      height: 15000px;
      max-height: 20vh;
      overflow-y: scroll;
      scrollbar-color: rebeccapurple green;
      scrollbar-width: thin;
      }</style>
      <div class="messages border">

      </div>
      <div class="userInputs">
        <form onsubmit="return postMessage(event)">
          <input type="hidden" class="form-control" name="author" id="author" placeholder="<?php echo $_SESSION['info']['username']?>" disabled>
          <input type="text"  class="form-control" id="content" name="content" placeholder="Ecris ton message !" required="required">
          <button type="submit" class="btn btn-primary mt-2">Envoyez !</button>
        </form>
      </div>
    </section>

    <script src="CSS/bootstrap-4.6.0-dist/js/messages.js"></script>

    <!-- MAITENANT Y A PLUS CHAT CAR IL A MARCHER SUR LA ROUTE ET PAF LE CHAT -->
  <?php
  if (isset($_GET ["search"]) && !empty($_GET ["search"])) {
    $search = $_GET ["search"];
    $result = [];
  echo ($result);
  }

   ?>


<div id="boutton">
  <div class="row">
      <div class="col-12 text-right">
          <?php


          if ($isCaptaine["isCaptain"] == 1) {
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
            echo '<button type="button" class="btn btn-primary text-white" disabled >Invité des membres</button>';
            echo '</div>';
            echo '</div>';
            echo '<div class="row">';
            echo '<div class="col-12 text-right mt-2">';
            echo '<button type="button" class="btn btn-primary" style="background-color:'.$color.'; border-color: '.$color.'" disabled>Chercher un match</button>';
            echo "</div>";
            echo "</div>";
          }
          ?>
      </div>
      </div>
    </div>
  </div>
</div>


<!-- modal invitation des membres-->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Sélectionner les membres de son équipe </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form class="form-inline" method="get">
            <input  class="form-control mr-sm-2" type="searchMembers" placeholder="Rechercher" onkeydown="searchMembresTeam()" id="searchMembers" >
          </form>
          <div class=" row text-center">
            <p class="font-italic mx-5 my-2">Voici une liste de tous les joueurs sur la platforme</p>
          </div>
          <div class="row my-2">
            <table class="table-borderless align-items-center">
              <form action="addToTeam.php" method="POST">
                <tbody id="modalMembres"  class="justify-content-between">
                  <?php
                    $connection = connectDB();
                    $queryPrepared = $connection->prepare("SELECT Username FROM ".PRE."User");
                    $queryPrepared->execute();
                    $listOfUser = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);
                    foreach ($listOfUser as $users => $infousers ) {
                      foreach ($infousers as $cle => $info) {
                      if ( $cle == "Username" && $info != $_SESSION["info"]["username"]) {
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
                      }?>
                </tbody>
            </table>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="submit" name="envoyer" class="btn btn-primary" onclick="">Envoyer une invitation</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>

<script src="CSS\bootstrap-4.6.0-dist\js\Prod.js" charset="utf-8"></script>


<?php include "footer.php" ?>
