<?php include "header.php";

if ( $_SESSION["auth"]!=true){

  echo'
  <div class="container">
    <div class="row ">
      <div class="col-12 text-center mt-5">
        <h1><u><a class="text-black" href="login.php">Veuillez vous connecter pour créer une nouelle équipe !</a></u></h1>
      </div>
    </div>
  </div>';

}else {

if (count($_POST)==2 && !empty($_POST["choix"]) && !empty($_POST["name"])) {
    $name = strtoupper(trim($_POST["name"]));
    $jeux = trim($_POST["choix"]);
    $listOfErrors = [];


      if( strlen($name)<3 || strlen($name)>25 ) {
    		$listOfErrors[] = "Votre nom d'équipe doit faire minimum 5 caractéres";
    	}else{
//Vérifier l'unicté du nom de l'équipe
    		$connection = connectDB();
    		$queryPrepared = $connection->prepare("SELECT name FROM KEMPLAY_Team WHERE name=:name");
    		$queryPrepared->execute(["name"=>$name]);
    		if( $queryPrepared->rowCount() != 0 ){
    			$listOfErrors[] =  "Votre nom d'équipe existe déjà";
    		}
    	}

      if ($jeux=="Choisissez...") {
        $listOfErrors[] =  "Entrer un nom de jeux";
      }

//insertion en BDD
if(empty($listOfErrors) ){
    		/*$queryPrepared =  $connection->prepare("INSERT INTO ".PRE."Team (name, game) VALUES (:name, :game);");
    		$queryPrepared->execute(["name"=>$name, "game"=>$jeux]);
        */
        $_SESSION["create team"]["name"] = $name;
        $_SESSION["create team"]["game"] = $jeux;

        header("Location:EditFlag.php");

      } else {
      		//Afficher les erreurs sur la page form.php
      		$_SESSION["listOfErrors"] = $listOfErrors;
      	}
}

?>
<section>
  <hr class=" mt-5"/>
      <h1 class="text-center">GESTIONNAIRE D'ÉQUIPE</h1>
  <hr/>

  <?php
  if(isset($_SESSION["listOfErrors"]) && !empty($_SESSION["listOfErrors"])){

      echo '<div class="alert alert-danger mt-4 col-md-10 offset-md-1" >';

      foreach ($_SESSION["listOfErrors"] as $error) {
        echo "<li>".$error;
      }

      unset($_SESSION["listOfErrors"]);
      echo "</div>";
  }
  ?>

  <div class="container">
    <div class="row align-item-center">
      <div class="col-6">
          <h3 class="py-3 font-weight-bold">Nom de l'équipe * </h3>
      </div>
    </div>
    <form method="POST">
  	<div class="form-group row">
    	<div class="col-sm-6">
      	<input type="text" class="form-control" name="name" required="required" placeholder="Entrer votre nom d'équipe">
    	</div>

	</div>

	<!--Sélecteur de jeux -->
	<div class="row mt-5">
		<div class="col ">
			<h3 class="py-3 font-weight-bold">Choisissez le jeux auquel vous souhaitez jouer *</h3>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-6">

      <div class="input-group mb-3">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" >Game</button>
        </div>
        <select class="custom-select" id="inputGroupSelect01" name="choix" value=""     required>
          <option selected>Choisissez...</option>
          <option value="League Of Legends">League Of Legends</option>
          <option value="Super Smash Bros">Super Smash Bros</option>
          <option value="Rocket League">Rocket League</option>
        </select>
      </div>

    </div>
  </div>
  <button type="submit" class="btn btn-primary my-5">Suivant</button>
  </form>



</section>
<?php }include "footer.php" ?>
