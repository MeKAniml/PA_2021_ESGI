<?php
include "header.php";
if ( $_SESSION["auth"]!=true ){
	header('Location:index.php');
	exit;
}
	//$afficherProfil = $connection ->prepare("SELECT * FROM KEMPLAY_User WHERE username=?", array($_SESSION["info"]["username"]));
	//$afficherProfil = $afficherProfil->fetch();

	if (count($_POST)==2 && isset($_POST["describe"]) && !empty($_POST["color"])) {


		$description = trim($_POST["describe"]);
		$color = $_POST["color"];

		$connection = connectDB();
		$queryPrepared =  $connection->prepare("UPDATE ".PRE."User SET description=:description, color=:color WHERE id_user =:id");
		$queryPrepared->execute(["description"=>$description, "color"=>$color, "id"=>$_SESSION["info"]["id_user"]]);
		$_SESSION["info"]["description"] = $description;
		$_SESSION['info']['color'] = $color;
	}
?>
<div class="container">
  <div class="row">

    <div class="col-9 mt-4">
      <h1 class=" font-weight-bold">Mon espace utilisateur</h1>


        <div class="row ">
          <div class="col- my-5 ">

            <form class="mx-4" action="Profil.php" method="POST">

							<div class="form-group">
					      <label for="inputEmail">Pseudo</label>
								<input class="form-control formStyle" type="email" name="mail"  disabled="disabled" placeholder="email" value="<?php echo $_SESSION['info']['username'];  ?>">
					    </div>

							<div class="form-group">
					      <label for="inputEmail">Email</label>
								<input class="form-control formStyle" type="email" name="mail"  disabled="disabled" placeholder="email" value="<?php echo $_SESSION['info']['email']; ?>">
								<a class="mt-4" href="Editmail.php"> Mettre à jour l'adresse email</a>
					    </div>

							<div class="form-group">
					      <label for="inputdate">Date de naissance</label>
								<input class="form-control formStyle" type="date" name="date" disabled="disabled"  value="<?php echo $_SESSION['info']['birthdate'];?>">
								<a class="mt-4" href="EditBirthdate.php"> Modifier le date de naissance</a>
					    </div>

              <div class="form-group">
								<label for="inputpwd">Mot de passe</label>
                <input class="form-control formStyle"  type="password" disabled="disabled" name="pwd" placeholder="mot de passe actuel" value="********">
                <a class="mt-4" href="Editpwd.php"> Modifier mon mot de passe</a>
              </div>


							<div class="form-group">
					      <label for="inputdescribe">Description</label>
								<textarea class="form-control formStyle" name="describe" rows="6" cols="45" placeholder="Ma description"><?php if (isset($_SESSION["info"]["description"])){echo $_SESSION["info"]["description"];}?></textarea>
					    </div>
							<input  type="hidden" id="color" name="color" value="<?php echo $_SESSION['info']['color']?>"/>

							<div class="form-check">
								<button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
							</div>

            </form>

          </div>
        </div>

    </div>

		<div class="col-3 mt-5 mb-5 text-center " id="changeColor" >
<?php
			define('TARGET', 'Images/Profil/');    // Repertoire cible
			define('MAX_SIZE', 10000000);    // Taille max en octets du fichier
			define('WIDTH_MAX', 1500);    // Largeur max de l'image en pixels
			define('HEIGHT_MAX', 1500);

			$tabExt = array('jpg','png','jpeg');
			$message = "";

			if (!empty($_FILES['fichier']['name'])) {

				// Recuperation de l'extension du fichier
    		$extension  = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION);

				// On verifie l'extension du fichier
			 if(in_array(strtolower($extension),$tabExt)){

				 // On recupere les dimensions du fichier
				 $infosImg = getimagesize($_FILES['fichier']['tmp_name']);
				 // On verifie le type de l'image
	      if($infosImg[2] >= 1 && $infosImg[2] <= 14){
					// On verifie les dimensions et taille de l'image
        	if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['fichier']['tmp_name']) <= MAX_SIZE)){
						// Parcours du tableau d'erreurs
          	if(isset($_FILES['fichier']['error']) && UPLOAD_ERR_OK === $_FILES['fichier']['error']){
							// On renomme le fichier
            	$nomImage = md5(uniqid()) .'.'. $extension;
							// Si c'est OK, on teste l'upload
            	if(move_uploaded_file($_FILES['fichier']['tmp_name'], TARGET.$nomImage)){

								$connection = connectDB();
								$queryPrepared =  $connection->prepare("UPDATE ".PRE."User SET image=:image WHERE id_user=:id");
								$queryPrepared->execute(["image"=>TARGET.$nomImage, "id"=>$_SESSION["info"]["id_user"]]);
								if ($_SESSION["info"]["image"] != 'Images\Profil\default.png') {
									unlink($_SESSION["info"]["image"]);
								}

								$_SESSION["info"]["image"] = TARGET.$nomImage;

								echo '<div class="alert alert-success mt-4 col-md-10 offset-md-1" >';
						    echo 'Upload réussi !';
								echo "</div>";




            	}else{
              // Sinon on affiche une erreur systeme
              $message = 'Problème lors de l\'upload !';
            	}
						}else{
            	$message = 'Une erreur interne a empêché l\'uplaod de l\'image';
          	}
					}else{
	          // Sinon erreur sur les dimensions et taille de l'image
	          $message = 'Taille max de l\'image: 1000x1000';
	        }
				}else{
	        // Sinon erreur sur le type de l'image
	        $message = 'Format autorisé : jpg, png, jpeg';
	      }
			}else{
	      // Sinon on affiche une erreur pour l'extension
	      $message = 'L\'extension du fichier est incorrecte !';
	    }
		}

		if(!empty($message)){
			echo '<div class="alert alert-danger mt-4 col-md-10 offset-md-1" >';
	    echo $message;
			echo "</div>";

		}
?>

			<img class="cercle" src="<?php echo $_SESSION["info"]["image"] ?>"/>
			<form method="post" enctype="multipart/form-data">
				<label class="mt-3 ml-5 form-label btn btn-primary btn-sm" id="label">
					<input type="file" id="images" style="display: none;" name="fichier" accept="image/png,image/jpeg,image/gif">
					Modifier
				</label>
				<button type="submit" class="btn btn-primary mt-3 ml-5">Valider</button>
			</form>

					<div class="col-12 mt-5 mb-5 text-center " id="changeColor"  style="color: <?php echo $_SESSION['info']['color']?>;">
						<label for="exampleColorInput" class="form-label">Couleur de profil</label>
						<svg class="mt-3" width="143" height="101" viewBox="0 0 143 101" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="143" height="101">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M15.8889 0.404498C15.8889 9.3404 8.77519 16.5844 0 16.5844V100.315H143L143 16.5844C134.225 16.5844 127.111 9.34041 127.111 0.40451C127.111 0.269279 127.113 0.134436 127.116 0H15.884C15.8873 0.134432 15.8889 0.269272 15.8889 0.404498Z" fill="#ED2E7E"/>
							</mask>
							<g mask="url(#mask0)">

								<path id="pathblason"  style="fill:<?php echo $_SESSION['info']['color']?>;" d="M143 0H0V100.315H143V0Z" />
							</g>
						</svg>

						<div class="ml-5 mt-4 mb-2">
				      <input onchange="changeColor()" type="color" class="form-control form-control-color" id="colorPicker" value="#563d7c" title="Choose your color">
						</div>

					</div>


		</div>

  </div>
</div>

<script src="CSS\bootstrap-4.6.0-dist\js\Prod.js" charset="utf-8"></script>
<?php
  include "footer.php"
?>
