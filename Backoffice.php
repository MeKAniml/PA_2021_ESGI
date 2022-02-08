<?php
	require "functions.php";
	session_start();
	if ($_SESSION["superAuth"] != true) {
		header('Location: Backlogin.php');
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

			<!-- NAVBAR -->
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<div class="container">
				<a class="navbar-brand" href="index.php">
					<img src="Images\logo + nom blanc.svg" width="80rem" class="mr-5"/>
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
					<div class="navbar-nav">
						<a class="nav-link active" href="#membres">Membres <span class="sr-only">(current)</span></a>
						<a class="nav-link active" href="#teams">Equipes</a>
						<a class="nav-link active" href="#stats">Stats</a>
						<a class="nav-link active" href="#contact">Support</a>
					</div>
				</div>
				</div>
				<a class="nav-link active text-white" aria-current="page" href="logout.php">
					<button class="btn btn-outline-success my-2 font-weight-bolder" type="button" name="signUp">Déconnexion</button>
				</a>
			</nav>

			<div class="container">
				<!-- Members section -->
				<div class="row">
					<div class="boxBack shadow border col-md mt-5 mb-5">
						<div class="row">
							<h3 class="offset-md-4 col-md-4 mt-4 font-weight-bolder mt-2" id="membres">Membres</h3>
							<form class="form-inline my-2 my-lg-0 offset-1 col-md-2">
					      <input onkeyup="searchMembres()" class="form-control mr-sm-2" id="searchMembers" type="searchMembers" placeholder="Rechercher" aria-label="search">
					    </form>
						</div>
						<div class="row">
							<div class="offset-md-1 col-md-10">

								<table class="table table-hover my-4">
									<thead>
										<tr>
											<th scope="col">ID</th>
											<th scope="col">Username</th>
											<th scope="col">Email</th>
											<th scope="col">date de naissance</th>
											<th scope="col">statut ban</th>
											<th scope="col">statut arbitre</th>
											<th scope="col">statut admin</th>
											<th scope="col">parametre</th>
										</tr>
									</thead>
									<tbody id="selectMembers">

										<?php
											$connection = connectDB();
											$queryPrepared = $connection->prepare("SELECT Id_user, Username, Email, Birthdate, isBan, isRef, superAdmin FROM ".PRE."User");
											$queryPrepared->execute();
											$results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

											foreach ($results as $users => $infousers ) {
												foreach ($infousers as $cle => $info) {
													if ($cle == "Id_user") {
														echo "<th scope=row>".$info."</th>";
													} elseif ($cle == "Email" || $cle == "Username") {
														echo "<td>".$info."</td>";
													} elseif ($cle == "Birthdate") {
															if(empty($info)){
																echo "<td> N/A </td>";
															} else{
																echo "<td>".$info."</td>";
															}
													}elseif ($cle== "isBan") {
														if ($info==1){
														echo "<td>ban</td>";
													}else {
														echo"<td></td>";
													}
												}elseif ($cle== "isRef") {
														if ($info==1){
														echo "<td>arbitre</td>";
													}else {
														echo"<td></td>";
													}
												}elseif ($cle== "superAdmin") {
														if ($info==1){
														echo "<td>admin</td>";
													}else {
														echo"<td></td>";
													}
													}

												}
												foreach ($infousers as $cle => $info) {
													if ($cle == 'Id_user') {
														echo '<div class="dropdown">';

														  echo '<td><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
														    echo '<img src="Images\settings.svg" width="20px"  id='.$info.'>';
														  echo '</button>';
														  echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
														    echo '<a onclick="changeStatus(1,'.$info.',1)" class="dropdown-item" src="">Bannir</a>';
														    echo '<a onclick="changeStatus(2,'.$info.',1)" class="dropdown-item" href="#">Promouvoir Admin</a>';
														    echo '<a onclick="changeStatus(3,'.$info.',1)" class="dropdown-item" href="#">Promouvoir Arbitre</a>';
																 echo '<a onclick="changeStatus(4,'.$info.',1)" class="dropdown-item" href="#">Débannir</a>';
																 echo '<a onclick="changeStatus(5,'.$info.',1)" class="dropdown-item" href="#">Retirer Admin</a>';
																 echo '<a onclick="changeStatus(6,'.$info.',1)" class="dropdown-item" href="#">Retirer Arbitre</a>';
														  echo '</div>';

														echo '</div></td>';

													}
												}
												echo "</tr>";
											}
											 ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<!-- Equipes section -->
				<div class="row">
					<div class="boxBack shadow border col-md mt-5 mb-5">
						<div class="row">
							<h3 class="offset-md-4 col-md-4 font-weight-bolder mt-2" id="teams">Equipes</h3>
							<form class="form-inline my-2 my-lg-0 offset-1 col-md-2">
								<input onkeyup="searchEquipes()" class="form-control mr-sm-2" id="searchTeams" type="searchTeams" placeholder="Rechercher" aria-label="search">

					    </form>
						</div>
						<div class="row">
							<div class="offset-md-1 col-md-10">

								<table class="table table-hover my-4">
									<thead>
										<tr>
											<th scope="col">ID</th>
											<th scope="col">Nom</th>
											<th scope="col">description</th>
											<th scope="col">statut ban</th>
											<th scope="col">capitaine</th>
										</tr>
									</thead>
									<tbody id="selectTeams">

										<?php
											$connection = connectDB();
											$queryPrepared = $connection->prepare("SELECT ".PRE."Team.id_team,".PRE."Team.name,".PRE."Team.description,".PRE."Team.isBan,".PRE."User.username FROM ".PRE."TeamUser INNER JOIN ".PRE."Team ON ".PRE."TeamUser.Team = ".PRE."Team.id_team INNER JOIN ".PRE."User ON ".PRE."TeamUser.User = ".PRE."User.id_user WHERE ".PRE."TeamUser.isCaptain=1");
											$queryPrepared->execute();
											$results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

											foreach ($results as $teams => $infoteams ) {
												foreach ($infoteams as $cle => $info) {
													if ($cle == "id_team") {
														echo "<th scope=row>".$info."</th>";
													} elseif ($cle == "name") {
														echo "<td>".$info."</td>";
													} elseif ($cle == "description") {
															if(empty($info)){
																echo "<td> N/A </td>";
															} else{
																echo "<td>".$info."</td>";
															}
													}elseif ($cle== "isBan") {
														if ($info==1){
														echo "<td>ban</td>";
													}else {
														echo"<td></td>";
													}
												}elseif ($cle=="username") {
													echo"<td>".$info."</td>";
												}

												}
												foreach ($infoteams as $cle => $info) {
													if ($cle == 'id_team') {
														echo '<div class="dropdown">';

															echo '<td><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
																echo '<img src="Images\settings.svg" width="20px"  id='.$info.'>';
															echo '</button>';
															echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
																echo '<a onclick="changeStatus(1,'.$info.',2)" class="dropdown-item" src="">Bannir</a>';
																echo '<a onclick="changeStatus(2,'.$info.',2)" class="dropdown-item" href="#">débannir</a>';

															echo '</div>';
														echo '</div></td>';

													}
												}
												echo "</tr>";

										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<!-- Statistiques section -->
				<div class="row">
					<div class="boxBack shadow border col-md mt-5 mb-5">
						<div class="row">
								<h3 class="offset-md-4 col-md-4 font-weight-bolder mt-2" id="stats">Statistiques</h3>
						</div>


						<!-- NOMBRE TOTAL DE VISITEUR -->
						<div class="row justify-content-around">
							<div class="border col-12 col-md-3  mt-3 p-2 shadow-sm">
								<p>
									<b>Nombre total de visiteur unique</b>
									<div class="row justify-content-center">
										<p class="font-weight-bolder display-4">
												<?php

													$queryPrepared = $connection->prepare("SELECT count(Ip) FROM ".PRE."Tracking;");
													$queryPrepared->execute();
													$results = $queryPrepared->fetch();
													echo $results[0];
												 ?>
										</p>
									</div>

								</p>
							</div>

							<!-- APPAREIL LES PLUS UTILISE -->
							<div class="border col-md-5 col-sm-6 mt-3 pt-2 shadow-sm">
								<p class="font-weight-bolder ">
									Appareil les plus utilisés
								</p>
								<?php

									$queryPrepared = $connection->prepare("SELECT device FROM ".PRE."Tracking;");
									$queryPrepared->execute();
									$results = $queryPrepared->fetchALL();
									$windows = 0;
									$ios = 0;
									$macOS = 0;
									$android = 0;
									$unix = 0;
									foreach ($results as $key => $device) {
										if (preg_match('#Windows#i', $device[0])) {
											$windows += 1;
										} elseif (preg_match('#Macintosh#i', $device[0])) {
											$macOS += 1;
										} elseif (preg_match('#iPhone#i', $device[0])) {
											$ios += 1;
										} elseif (preg_match('#Android#i', $device[0])) {
											$android += 1;
										} elseif (preg_match('#X11#i', $device[0])) {
											$unix += 1;
										}

									}

								 ?>
								<ul class="offset-3 col-5 pl-5 h5" >
									<li>
										Windows <?php echo $windows;?>

									</li>
									<li>
										macOS <?php echo $macOS;?>
									</li>
									<li>
										iOS <?php echo $ios;?>
									</li>
									<li>
										Android <?php echo $android;?>
									</li>
									<li>
										Unix <?php echo $unix;?>
									</li>
								</ul>
							</div>

							<!-- UTILISATEUR ACTIF-->
							<div class="border col-12 col-md-3 mt-3 p-2 shadow-sm">
								<?php

								$queryPrepared = $connection->prepare("SELECT lastConnect FROM ".PRE."User;");
								$queryPrepared->execute();
								$results = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);
								$cpt = 0;
								$currentTime = time();
								foreach ($results as $key ) {
									foreach ($key as $connect => $date) {
										$late = strtotime($date);
										if (($currentTime - $late) <= 5400){ // 5400 = 1H30 en seconde
											$cpt++;
										}
									}
								}
								 ?>
								<p>
									<b>Utilisation actif</b>
									<div class="row justify-content-center">
										<p class="font-weight-bolder display-4">
												<?php echo $cpt;?>
										</p>
									</div>
								</p>
							</div>
						</div>
					</div>
				</div>







				<!-- Contact section -->
				<div class="row">
					<div class="boxBack shadow border col-md mt-5 mb-5" >
						<div class="row">
							<h3 class="offset-md-4 col-md-4 mt-4 font-weight-bolder mt-2" id="contact">Contact</h3>
							<form class="form-inline my-2 my-lg-0 offset-1 col-md-2">
					      <input onkeyup="messages()" class="form-control mr-sm-2" id="searchMessages" type="search" placeholder="Rechercher" aria-label="Search">
					    </form>
						</div>
						<div class="row">
							<div class="offset-md-1 col-md-10">

								<table class="table table-hover my-4">
									<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Username</th>
											<th scope="col">Email</th>
											<th scope="col">Message</th>
										</tr>
									</thead>
									<tbody id="selectMessages">

										<?php
											$connection = connectDB();
											$queryPrepared = $connection->prepare("SELECT * FROM ".PRE."UserMessage ORDER BY Id DESC;");
											$queryPrepared->execute();
											$results = $queryPrepared->fetchALL();

											foreach ($results as $users => $infousers ) {
												foreach ($infousers as $cle => $info) {
													if ($cle == "id") {
														echo "<th scope=row>".$info."</th>";
													} elseif ($cle == "Email" || $cle == "Username") {
														echo "<td>".$info."</td>";
													} elseif ($cle == "Message") {
															if(empty($info)){
																echo "<td> N/A </td>";
															} else{
																echo "<td>".$info."</td>";
															}
													}
												}
												echo "</tr>";
											}
											 ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

<script src="CSS\bootstrap-4.6.0-dist\js\Backoffice.js" charset="utf-8"></script>

<?php
	include "footer.php";
?>
