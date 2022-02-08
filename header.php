<?php
  session_start();
  require "functions.php";

  if (!isset($_SESSION["auth"])) {
    $_SESSION["auth"]=false;
  };

  if (!isset($ip) && !isset($device)) {

    $ip= $_SERVER['REMOTE_ADDR'];
    $device = $_SERVER['HTTP_USER_AGENT'];

    //Vérifie si l'ip existe dans la db
    $connection = connectDB();
    $trackingQueryPrepared = $connection->prepare("SELECT * FROM ".PRE."Tracking WHERE Ip=:ip");
    $trackingQueryPrepared->execute(["ip"=>$ip]);
    $tracking = $trackingQueryPrepared->fetch();

    if (empty($tracking)) {
      $trackingQueryPrepared = $connection->prepare("INSERT INTO ".PRE."Tracking (Ip, device) VALUES (:ip, :device) ;");
      $trackingQueryPrepared->execute(["ip"=>$ip, "device"=>$device]);
    }
  }


  if ($_SESSION["auth"] == true && isset($_SESSION["info"])) {
    $time = date("Y-m-d H:i:s");
    $email = $_SESSION["info"]["email"];
    $dateQueryPrepared = $connection->prepare("UPDATE ".PRE."User SET `LastConnect`=:time WHERE email=:Email;");
    $dateQueryPrepared->execute(["time"=>$time, "Email"=>$email]);
  }
?>
<!DOCTYPE html>
<html lang="fr">
      <head>
      	<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="mise en relation d'équipe de sport">

        <link rel="shortcut icon" href="Images\logo.svg" type="image/x-icon">
        <!--bootstrap CSS -->
        <link rel="stylesheet" href="CSS\bootstrap-4.6.0-dist\css\bootstrap.css">
      	<title> PLAY - Let's game </title>
      </head>
      <body>
        <div class="main">
        <header>
          <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <div class="container-fluid">
              <a class="navbar-brand text-green" href="index.php">PLAY</a>
              <button class="navbar-toggler" data-toggle="collapse" data-target="#menuhamburger">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end align-middle" id="menuhamburger">
                <ul class="navbar-nav align-items-center justify-content-end font-weight-bolder">
               <li class="nav-item">
                 <a class="nav-link active text-white" aria-current="page" href="index.php#game">Jeux</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link active text-white" aria-current="page" href="ShowTeam.php">Equipes</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link active text-white" aria-current="page" href="showMatchs.php">Matchs</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link active text-white" aria-current="page" href="classement.php">Classement</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link active text-white" aria-current="page" href="Game.php">Mini-Jeu</a>
               </li>
               <?php

               if ($_SESSION["auth"]==true) {
                 echo "<li>";?>
                    <a class="nav-link active text-white" aria-current="page" href="Profil.php">
                    <button class="btn btn-outline-success my-2 font-weight-bolder" type="button" name="login" >
                      <?php
                      echo $_SESSION["info"]["username"];
                      ?>
                    </button>
                    </a>
                 <?php
                  echo "</li>";
                  echo "<li>";?>
                    <a class="nav-link active text-white" aria-current="page" href="logout.php">
                      <button class="btn btn-outline-success my-2 font-weight-bolder" type="button" name="signUp">Déconnexion</button>
                    </a>
                  <?php
                  echo "</li>";
               }else{
                 echo "<li>";?>
                    <a class="nav-link active text-white" aria-current="page" href="login.php">
                    <button class="btn btn-outline-success my-2 font-weight-bolder" type="button" name="login">Connexion</button>
                    </a>
                 <?php
                  echo "</li>";
                  echo "<li>";?>
                    <a class="nav-link active text-white" aria-current="page" href="newUser.php">
                      <button class="btn btn-outline-success my-2 font-weight-bolder" type="button" name="signUp">S'inscrire</button>
                    </a>
                  <?php
                  echo "</li>";}
                  ?>
              </div>
            </div>

          </ul>
          </nav>
        </header>
