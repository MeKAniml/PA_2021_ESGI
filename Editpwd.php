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

					<h4 class="font-weight-bolder"> Modifier le mot de passe </h4>

          <?php
          if( count($_POST)==3 && !empty($_POST["confirmnewpass"]) && !empty($_POST["newpass"]) && !empty($_POST["oldpass"])){

						//Afficher OK si les identifiants sont bons sinon afficher NOK
						//password_verify

            $login = $_SESSION["info"]["email"];
						$oldpwd = $_POST["oldpass"];
            $newpwd = $_POST["newpass"];
            $confirm = $_POST["confirmnewpass"];
            $listOfErrors = [];

						$connection = connectDB();
						$queryPrepared = $connection->prepare("SELECT * FROM ".PRE."User WHERE email=:login");
						$queryPrepared->execute(["login"=>$login]);
						$results = $queryPrepared->fetch();


						if(!password_verify($oldpwd, $results["pwd"])){
							$listOfErrors[] =  "Le mot de passe actuel est incorrect";
						}


          if( strlen($newpwd)<=6
        		|| !preg_match("#[a-z]#", $newpwd)
        		|| !preg_match("#[A-Z]#", $newpwd)
        		|| !preg_match("#[0-9]#", $newpwd)
        	 ) {
        		$listOfErrors[] =  "Veuillez entrer un mot de passe respectant les règles ci-dessous";
        	}


          if( $newpwd != $confirm){
        		$listOfErrors[] =  "Mot de passe de confirmation incorrects";
        	}

          if ($newpwd == $oldpwd) {
            $listOfErrors[] =  "Vous avez utilisé un ancien mot de passe";
          }

          //insertion en BDD
          if( empty($listOfErrors) ){

        		$queryPrepared =  $connection->prepare("UPDATE ".PRE."User SET pwd=:pwd WHERE id_user =:id");
        		$pwd = password_hash($newpwd, PASSWORD_DEFAULT);

        		$queryPrepared->execute(["pwd"=>$pwd, "id"=>$_SESSION["info"]["id_user"]]);
						$_SESSION["info"]["pwd"] = $pwd;
            header("Location: Profil.php");

          }else {
          	$_SESSION["listOfErrors"] = $listOfErrors;
          }
        }
        if(isset($_SESSION["listOfErrors"])){

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
                  <div class="col-5 ">
                    <div class="text-right" >Mot de passe actuel :</div>
                  </div>
                  <div class="col-7">
                    <input type="password" name="oldpass" class="form-control">
                  </div>
                </div>
							</div>

              <div class="offset-md-1 col-md-10 mt-3 mb-2">
                <div class="input-group">
                  <div class="col-5 ">
                    <div class="text-right" >Nouveau mot de passe :</div>
                  </div>
                  <div class="col-7">
                    <input type="password" name="newpass" class="form-control">
                  </div>
                </div>
							</div>

              <div class="offset-md-1 col-md-10 mt-3 mb-5">
                <div class="input-group">
                  <div class="col-5 ">
                    <div class="text-right" >Retapez le mot de passe :</div>
                  </div>
                  <div class="col-7">
                    <input type="password" name="confirmnewpass" class="form-control">
                  </div>
                </div>
							</div>

<!--
							<p class="mb-3 offset-md-1 col-md-10 mb-4" style="text-align: left; font-size:0.80rem">
								Mot de passe oublié.
							</p>
-->

              <p class="offset-md-1 col-md-5 text-left">
                Regles de sécurité
              </p>
              </div>
              <div class="row">
            <ol class="mb-3 offset-md-1 col-md-5 text-left" style="font-size:0.80rem;">
              <li>
                Utilisez entre 6 et 30 caractères
              </li>
              <li>
                Au moins une lettre minuscule et une lettre majuscule
              </li>
              <li>
                Un chiffre
              </li>
            </ol>
						</div>


						<div class="row">

							<div class="form-check col mb-5">
								<button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
							</div>

						</div>

					</form>
				</div>
			</div>
		</div>



<?php
	include "footer.php";
?>
