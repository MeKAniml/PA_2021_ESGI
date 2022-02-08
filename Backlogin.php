<?php
	require "functions.php";
	session_start();

if( count($_POST)==2 && !empty($_POST["login"]) && !empty($_POST["pwd"])){

	//Afficher OK si les identifiants sont bons sinon afficher NOK
	//password_verify

	$login = $_POST["login"];
	$pwd = $_POST["pwd"];

	$connection = connectDB();
	$queryPrepared = $connection->prepare("SELECT * FROM ".PRE."User WHERE email=:login");
	$queryPrepared->execute(["login"=>$login]);
	$results = $queryPrepared->fetch();


	if(empty($results)){
		//echo '<div class="alert alert-danger">Identifiants incorrects</div>';
	}elseif ($results["isBan"] == 1) {
		echo '<div class="alert alert-danger">Vous êtes BAN (sorry or not sorry)</div>';
	}else if( password_verify($pwd, $results["pwd"]) && $results["superAdmin"] == 1){

		$_SESSION["superAuth"]=true;
		header("Location: Backoffice.php");
		//echo '<div class="alert alert-sucess">Connexion réussie</div>';

	}else{
		//echo '<div class="alert alert-danger">Identifiants incorrects</div>';
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Back-office Play site">

  <link rel="shortcut icon" href="Images\logo.svg" type="image/x-icon">
  <!--bootstrap CSS -->
  <link rel="stylesheet" href="CSS\bootstrap-4.6.0-dist\css\bootstrap.css">
  <title> PLAY - Back-office </title>

</head>
<body>
  <div class="container">
    <div class="row">

      <div class="box shadow-sm border col-md-4 p-3 my-5">

        <h1 class="text-green" style="font-family: Roboto" > PLAY </h1>
        <h4 class="font-weight-bolder"> Backoffice </h4>



        <form method="POST">
          <div class="row">

            <div class="offset-md-1 col-md-10 mt-3 mb-2">
              <input type="text" class="form-control" name="login" required="required" placeholder="Adresse e-mail ou pseudo">
            </div>

            <div class="offset-md-1 col-md-10 mb-1">
              <input type="password" class="form-control" name="pwd" required="required" placeholder="Mot de passe">
            </div>

          </div>


          <div class="row justify-content-end">


            <div class="form-check mr-5  mb-4 mt-3">
              <button type="submit" class="btn btn-primary">Valider</button>
            </div>

          </div>

        </form>
      </div>
    </div>
  </div>

  <body>
</html>
