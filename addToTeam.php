<?php
session_start();
require "functions.php";
  if (isset($_POST["option"])) {

    $option=$_POST["option"];
    echo "<pre>";
    print_r($option);
    echo "</pre>";
    $connection=connectDB();
    foreach ($option as $key => $value) {

      $queryPrepared=$connection->prepare("SELECT id_user, email, username FROM ".PRE."User WHERE  username = :username;");
      $queryPrepared -> execute(["username"=>$value]);
      $info_user= $queryPrepared->fetch(PDO::FETCH_ASSOC);
      echo "<pre>";
      print_r($info_user);
      echo "</pre>";



      $queryPrepared=$connection->prepare("INSERT INTO ".PRE."TeamUser (User,Team) VALUES ( :id_user, :id_team);");
      $queryPrepared -> execute(["id_user"=>$info_user["id_user"], "id_team"=>$_SESSION["team"]["id_team"]]);

      $message = '<html>
       <head>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


        <title>Invitation &agrave une partie</title>
       </head>
       <body>
        <p>
          Bonjour, l equipe '.$_SESSION["team"]["name"].' vous invite &agrave  jouer avec vous &agrave  '.$_SESSION["team"]["game"].'
        </p>
        <p> Pour les rejoindrent rendez-vous sur https://play.kccorp.fr  </p>
       </body>
      </html>';
      sendMail($info_user["email"],$message);
      header("Location: EditTeam2.php?name=".$_SESSION["team"]["name"]);
      die();


    }
} else {
  header("Location: index.php");
}
;
?>
