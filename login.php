<?php
	include "header.php";
?>

		<div class="container">
			<div class="row">

				<div class="box shadow border col-md-4 p-3 my-5">

					<h1 class="text-green" style="font-family: Roboto" > PLAY </h1>
					<h4 class="font-weight-bolder"> Se connecter </h4>

					<?php

					if( count($_POST)==2 && !empty($_POST["login"]) && !empty($_POST["pwd"])){
						//Afficher OK si les identifiants sont bons sinon afficher NOK
						//password_verify

						$login = $_POST["login"];
						$pwd = $_POST["pwd"];

						$connection = connectDB();
						$queryPrepared = $connection->prepare("SELECT * FROM ".PRE."User WHERE email=:login");
						$queryPrepared->execute(["login"=>$login]);
						$results = $queryPrepared->fetch(PDO::FETCH_ASSOC);


						if(empty($results)){
							echo '<div class="alert alert-danger">Identifiants incorrects</div>';
						}elseif ($results["isBan"] == 1) {
							echo '<div class="alert alert-danger">Vous êtes BAN (sorry or not sorry)</div>';
						}else if( password_verify($pwd, $results["pwd"])){

							$_SESSION["auth"]=true;
							$_SESSION["info"]=$results;
							echo '<div class="alert alert-sucess">Connexion réussie</div>';
							header("Location: captcha.php?login=1&pwd=1");
						}else{
							echo '<div class="alert alert-danger">Identifiants incorrects</div>';
						}
					}

						if (isset($_SESSION["listOfErrors"])) {
							echo "<div class='alert alert-danger' >";
							 echo $_SESSION["listOfErrors"];
							echo '</div>';
							unset($_SESSION["listOfErrors"]);
						}
					?>

					<form method="POST">
						<div class="row mb-4">

							<div class="offset-md-1 col-md-10 mt-3 mb-2">
								<input type="text" class="form-control" name="login" required="required" placeholder="Adresse e-mail">
							</div>

							<div class="offset-md-1 col-md-10 mb-1">
								<input type="password" class="form-control" name="pwd" required="required" placeholder="Mot de passe">
							</div>

<!--
							<p class="mb-3 offset-md-1 col-md-10 mb-4" style="text-align: left; font-size:0.80rem">
								Mot de passe oublié.
							</p>
-->
						</div>


						<div class="row mb-4">

							<a href="newUser.php" class="offset-md-1 col-md-6" style="text-align: left"><u>S'inscrire</u></a>

							<div class="form-check col-md-2 ">
								<button type="submit" class="btn btn-primary">Valider</button>
							</div>

						</div>

					</form>
				</div>
			</div>
		</div>

<?php
	include "footer.php";
?>
