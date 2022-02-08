<?php
include "header.php";
//si vald est pas rempli ou = 2 => redirection et destruction de la session , sinon direction profil.php
if (empty($_GET['vald'])) {

  session_destroy();
  $_SESSION["listOfErrors"]="captcha invalide";
  header('Location: login.php');

}elseif ($_GET['vald']==1) {
  
  $_SESSION["auth"]=true;
  header('Location: Profil.php');

}elseif ($_GET['vald']==2) {

  session_destroy();
  $_SESSION["listOfErrors"]="captcha invalide";
  header('Location: login.php');

}
include "footer.php";
 ?>
