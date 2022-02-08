<?php
	include "header.php";
	if ( $_SESSION["auth"]!=true){
  	header('Location:index.php');
  	exit;
  }
?>

		<div class="container">
			<div class="row">

				<div class="box shadow border col-md-10 p-3 my-5">

					<h4 class="font-weight-bolder"> Mettre à jour l'adresse email </h4>

          <?php
          if( count($_POST)==3 && !empty($_POST["newmail"]) && !empty($_POST["confirmmail"]) && !empty($_POST["pwd"])){

						//Afficher OK si les identifiants sont bons sinon afficher NOK
						//password_verify


            $email = mb_strtolower(trim($_POST["newmail"]));
            $confirmmail = mb_strtolower(trim($_POST["confirmmail"]));
            $pwd = $_POST["pwd"];
            $listOfErrors = [];


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


						if(!password_verify($pwd, $_SESSION["info"]["pwd"])){
							$listOfErrors[] =  "Le mot de passe est incorrect";
						}


          if( $email != $confirmmail){
        		$listOfErrors[] =  "Email de confirmation incorrects";
        	}

          //insertion en BDD
          if( empty($listOfErrors) ){

        		$queryPrepared =  $connection->prepare("UPDATE ".PRE."User SET email=:email, `Confcode`=:code, `Check`=:check WHERE id_user =:id");
						$code = rand(1000, 9999);
        		$queryPrepared->execute(["email"=>$email, "code"=>$code , "check"=>0 ,"id"=>$_SESSION["info"]["id_user"]]);
						$_SESSION["info"]["email"] = $email;
						$_SESSION["info"]["Confcode"]=$code;

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
						sendMail($email,$message);
            header("Location: CodeConf.php");


          }else {
          	$_SESSION["listOfErrors"] = $listOfErrors;
          }

        }
        if(isset($_SESSION["listOfErrors"]) && !empty($_SESSION["listOfErrors"])){

            echo '<div class="alert alert-danger mt-4 col-md-10 offset-md-1" >';

            foreach ($_SESSION["listOfErrors"] as $error) {
              echo "<li>".$error;
            }

            unset($_SESSION["listOfErrors"]);
            echo "</div>";
        }



					?>

					<form method="POST">
						<div class="row">

							<div class="offset-md-1 col-md-10 mt-3 mb-2">
                <div class="input-group">
                  <div class="col-4 ">
                    <div class="text-right" >Nouvel email :</div>
                  </div>
                  <div class="col-7">
                    <input type="email" name="newmail" class="form-control">
                  </div>
                </div>
							</div>

              <div class="offset-md-1 col-md-10 mt-3 mb-2">
                <div class="input-group">
                  <div class="col-4 ">
                    <div class="text-right" >Ressaisir le nouvel email :</div>
                  </div>
                  <div class="col-7">
                    <input type="email" name="confirmmail" class="form-control">
                  </div>
                </div>
							</div>

              <div class="offset-md-1 col-md-10 mt-3 mb-4">
                <div class="input-group">
                  <div class="col-4 ">
                    <div class="text-right" >Mot de passe :</div>
                  </div>
                  <div class="col-7">
                    <input type="password" name="pwd" class="form-control">
                  </div>
                </div>
							</div>

<!--
							<p class="mb-3 offset-md-1 col-md-10 mb-4" style="text-align: left; font-size:0.80rem">
								Mot de passe oublié.
							</p>
-->


							<div class="form-check col mb-4">
								<button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
							</div>


					</form>
				</div>
			</div>
		</div>
    </div>



<?php
	include "footer.php";
?>
