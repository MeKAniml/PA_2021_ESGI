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
          if( count($_POST)==2 && !empty($_POST["newBirthdate"]) && !empty($_POST["pwd"])){

						//Afficher OK si les identifiants sont bons sinon afficher NOK
						//password_verify


            $newBirthdate = mb_strtolower(trim($_POST["newBirthdate"]));
            $pwd = $_POST["pwd"];
            $listOfErrors = [];


            if( $pwd == $_SESSION['info']['birthdate'] ) {
          		$listOfErrors[] =  "Date de naissance identique";
          	}


						if(!password_verify($pwd, $_SESSION["info"]["pwd"])){
							$listOfErrors[] =  "Le mot de passe est incorrect";
						}


          //insertion en BDD
          if( empty($listOfErrors) ){

        		$queryPrepared =  $connection->prepare("UPDATE ".PRE."User SET birthdate=:birthdate WHERE id_user =:id");
        		$queryPrepared->execute(["birthdate"=>$newBirthdate, "id"=>$_SESSION["info"]["id_user"]]);
						$_SESSION["info"]["birthdate"] = $newBirthdate;

            header("Location: Profil.php");

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
                    <div class="text-right" >Date de naissance :</div>
                  </div>
                  <div class="col-7">
                    <input class="form-control formStyle" type="date" name="newBirthdate" id="inputdate" value="<?php echo $_SESSION['info']['birthdate'];?>">
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
