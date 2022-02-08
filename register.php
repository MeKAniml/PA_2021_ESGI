<?php
session_start();

require "functions.php";

if ( count($_POST) == 4
	&& !empty($_POST["pseudo"])
	&& !empty($_POST["email"])
	&& !empty($_POST["pwd"])
	&& !empty($_POST["pwdConfirm"])
) {

  $pseudo = trim($_POST["pseudo"]);
  $email = mb_strtolower(trim($_POST["email"]));
  $pwd = $_POST["pwd"];
	$pwdConfirm = $_POST["pwdConfirm"];


  $listOfErrors = [];


  if( strlen($pseudo)<3 || strlen($pseudo)>25 ) {
		$listOfErrors[] = "Votre pseudo doit faire minimum 5 caractéres";
	}else{

		//Vérifier l'unicté du pseudo
		$connection = connectDB();

		$queryPrepared = $connection->prepare("SELECT Username FROM ".PRE."User WHERE Username=:pseudo");

		$queryPrepared->execute(["pseudo"=>$pseudo]);

		if( $queryPrepared->rowCount() != 0 ){
			$listOfErrors[] =  "Votre pseudo existe déjà";
		}
	}


  if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
		$listOfErrors[] =  "Adresse mail invalide";

	}else{

		//Vérifier l'unicté de l'adresse email
		$connection = connectDB();

		$queryPrepared = $connection->prepare("SELECT email FROM ".PRE."User WHERE email=:email");

		$queryPrepared->execute(["email"=>$email]);

		if( $queryPrepared->rowCount() != 0 ){
			$listOfErrors[] =  "Votre email existe déjà";
		}
	}


  if( strlen($pwd)<=6
		|| !preg_match("#[a-z]#", $pwd)
		|| !preg_match("#[A-Z]#", $pwd)
		|| !preg_match("#[0-9]#", $pwd)
	 ) {
		 $listOfErrors[] =  "Votre mot de passe doit faire 6 caractères dont 1 minuscule, 1 majuscule et 1 chiffre";
	}


  if( $pwd != $pwdConfirm){
		$listOfErrors[] =  "Mot de passe de confirmation incorrects";
	}

  //insertion en BDD
  if( empty($listOfErrors) ){

		$queryPrepared =  $connection->prepare("INSERT INTO ".PRE."User (Username, Email, Pwd, Confcode) VALUES ( :pseudo, :email , :pwd, :code);");
		$pwd = password_hash($pwd, PASSWORD_DEFAULT);
		$code = rand(1000, 9999);
		$_SESSION["code"] = $code;
		$queryPrepared->execute(["pseudo"=>$pseudo, "email"=>$email, "pwd"=>$pwd, "code"=>$code]);


		$queryPrepared = $connection->prepare("SELECT * FROM ".PRE."User WHERE email=:email");
		$queryPrepared->execute(["email"=>$email]);
		$results = $queryPrepared->fetch();

		$_SESSION["info"]=$results;
		$_SESSION["auth"]=true;
		//$_SESSION["info"]["Confcode"]=$code;

		$message = '<html>
		 <head>
			<title>Code de confirmation Play.kccorp.fr</title>
		 </head>
		 <body>
			<p>
				Bonjour '.$_SESSION["info"]["username"].'
			</p>
			<p>
				Votre code confirmation est : '.$code.'
			</p>
			<p>
				Cordialement.
			</p>
		 </body>
		</html>
		';
		sendMail($_SESSION["info"]["email"],$message);
		header("Location: CodeConf.php");
	} else {
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrors"] = $listOfErrors;
		header("Location: newUser.php");
	}
} else {
	header("Location: index.php");
}
