<?php
	include "header.php";
?>

		<div class="container">
			<div class="row">

				<div class="box shadow border col-md-4 p-3 my-5">

					<h1 class="text-green" style="font-family: Roboto" > PLAY </h1>
					<h4 class="font-weight-bolder"> Se connecter </h4>

					<?php
					if( count($_POST)==1 && !empty($_POST["code"]) ){

						//Afficher OK si les identifiants sont bons sinon afficher NOK
						//password_verify

						$code = trim($_POST["code"]);

						if (isset($_SESSION["info"]["email"])) {
							$email = $_SESSION["info"]["email"];
						}else {
							$email = $_SESSION["email"];
						}

						$connection = connectDB();
						$queryPrepared = $connection->prepare("SELECT * FROM ".PRE."User WHERE Email=:login");
						$queryPrepared->execute(["login"=>$email]);
						$results = $queryPrepared->fetch(PDO::FETCH_ASSOC);


						if($results["Confcode"]!=$code){
							echo '<div class="alert alert-danger">Code incorrect</div>';
						}else if( $results["Confcode"] == $code ){

							$queryPrepared = $connection->prepare("UPDATE ".PRE."User SET `Check`=:check WHERE email=:email");
							$queryPrepared->execute(["check"=>1, "email"=>$email]);

							$_SESSION["auth"]=true;
							$_SESSION["info"]=$results;

							echo '<div class="alert alert-sucess">Connexion réussie</div>';
							header("Location: Profil.php");
						}else{
							echo '<div class="alert alert-danger">Identifiants incorrects</div>';
						}
					}

					?>

					<form method="POST">
						<div class="row">

							<div class="offset-md-1 col-md-10 my-4 mb-2">
								<input type="text" class="form-control" name="code" placeholder="Code de confirmation">
							</div>

						</div>

						<div class="row">

							<div class="form-check offset-md-4 col-md-4 align mb-4">
								<button type="submit" class="btn btn-primary center">Valider</button>
							</div>

						</div>

					</form>

				</div>
			</div>
		</div>

<?php
	include "footer.php";
?>
