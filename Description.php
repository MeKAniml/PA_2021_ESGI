<?php include "header.php";
/*if (!isset ($_SESSION['info'])){
	header('Location:index.php');
	exit;
}*/

if (isset($_SESSION["create team"]["color"]) && isset($_SESSION["create team"]["name"]) && isset($_SESSION["create team"]["game"]) ) {
  if (count($_POST)==1 ) {

      $description = trim($_POST["description"]);
      $listOfErrors = [];

        if( strlen($description)>255 ) {
      		$listOfErrors[] = "Votre description doit faire maximum 255 caractéres";
      	}


  //insertion en BDD
    if(empty($listOfErrors)){
            echo "OK";
        		$queryPrepared =  $connection->prepare("INSERT INTO ".PRE."Team (name, game, color, description) VALUES (:name, :game, :color, :description);");
        		$queryPrepared->execute(["name"=>$_SESSION["create team"]["name"], "game"=>$_SESSION["create team"]["game"], "color"=>$_SESSION["create team"]["color"], "description"=>$description]);

            $_SESSION["create team"]["description"] = $description;

            $queryPrepared =  $connection->prepare("SELECT id_team FROM ".PRE."Team WHERE name=:name;");
        		$queryPrepared->execute(["name"=>$_SESSION["create team"]["name"]]);


            $id_team = $queryPrepared->fetch(PDO::FETCH_ASSOC);


            $queryPrepared =  $connection->prepare("INSERT INTO ".PRE."TeamUser (Team, User, isCaptain) VALUES (:Team, :User, 1);");
        		$queryPrepared->execute(["Team"=>$id_team["id_team"], "User"=>$_SESSION["info"]["id_user"]]);


            header("Location:EditTeam2.php?name=".$_SESSION["create team"]["name"]);

          } else {
          		//Afficher les erreurs sur la page form.php
              echo "NOK";
          		$_SESSION["listOfErrors"] = $listOfErrors;
          	}

  }
} else {
  header("Location:index.php");
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

   <h1 class="font-weight-bold mt-5 text-center">Description</h1>
   <div class="container">
     <div class="row">
       <div class="col-4 media align-self-center mt-4">

         <svg class="mt-3" width="150" height="200" viewBox="0 0 208 279" fill="none" xmlns="http://www.w3.org/2000/svg">
           <g filter="url(#filter0_d)">
             <path d="M187.968 115.436H20V209.184C20 215.437 23.6421 221.116 29.3245 223.725L90.6332 251.871C99.1087 255.762 108.86 255.762 117.335 251.871L178.644 223.725C184.326 221.116 187.968 215.437 187.968 209.184V115.436Z" fill="white"/>
           </g>
           <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="20" y="0" width="168" height="116">
             <path fill-rule="evenodd" clip-rule="evenodd" d="M38.6639 1.10736C38.6639 11.3328 30.3081 19.6221 20.0008 19.6221C20.0005 19.6221 20.0003 19.6221 20 19.6221V115.436H187.968L187.968 19.6222C177.661 19.6222 169.305 11.3328 169.305 1.1074C169.305 0.95266 169.307 0.798363 169.311 0.644531H38.6582C38.662 0.798349 38.6639 0.952632 38.6639 1.10736Z" fill="white"/>
           </mask>
           <g mask="url(#mask0)">
             <path d="M187.968 0.644531H20V115.436H187.968V0.644531Z" fill="<?php echo $_SESSION['create team']['color']; ?>"/>
             </g>
             <defs>
               <filter id="filter0_d" x="0" y="99.4362" width="207.968" height="179.353" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                 <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                 <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                 <feOffset dy="4"/>
                 <feGaussianBlur stdDeviation="10"/>
                 <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>
                 <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                 <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
               </filter>
             </defs>
           </svg>

         </div>
         <form method="POST">
           <div class="col  mt-5">
             <textarea class="form-control formStyle"  name="description" rows="6" cols="75" placeholder="La description de mon équipe..."></textarea>
           </div>

           <div class="row align-items-end mt-5">
             <div class="col-6">
               <button type="button" name="button" class="btn btn-primary mb-5" href="EditFlag.php">Retour</button>
             </div>

             <div class="col-6 text-right">
               <button type="submit" class="btn btn-primary mb-5">Créer l'équipe</button>
             </div>


           </div>
         </form>

       </div>


     </section>

<script src="CSS\bootstrap-4.6.0-dist\js\Font.js" charset="utf-8"></script>

<?php include "footer.php" ?>
