<?php
	include "header.php";
?>

<!--Concept du site-->
          	<section>
                <div class="container my-5">
                  <div class="row">
                    <div class="col">
                      <h1 id="about"> <b>Qu'est ce que PLAY ? </b></h1>
                      <br>
                      <p><b>PLAY</b> est une jeune plateforme permettant aux joueurs de la France entière de se réunir et de passer du bon temps en se défiant sur plusieurs jeux vidéo. Avec notre algorithme de recherche surdéveloppé rendant jaloux les géants comme Tencent, soyez sûr que votre clavier ou votre manette restera en bon état. De plus, PLAY intègre un mini-jeu et une zone secrète permettant de combler l’attente entre deux matches.
											</p><p><b>N’attendez plus, créez votre équipe dès maintenant !</b></p>
                   </div>
                   <div class="offset-md-1">
                     <img class="mockup" src="Images/mockupiMac.png" alt="">
                   </div>
                 </div>
                </div>
              </section>

<!--Carousel-->
<?php

$queryPrepared = $connection->prepare("SELECT name, elo, game, color FROM ".PRE."Team WHERE game=:game ORDER BY elo DESC LIMIT 1;");

$queryPrepared->execute(["game"=>'league of Legends']);
$topTeam = $queryPrepared->fetch(PDO::FETCH_ASSOC);
?>
<section class="mb-5 mt-4">
	<div class="container">
		<div class="col-12">

			<div  class="carousel slide " data-ride="carousel">
				<div class="carousel-inner">


					<div class="carousel-item active" data-interval="7000"> <!-- Start of #1 carousel -->

						<div class="row ">

							<div class="col-2 ">
								<img src="Images/leagueoflegends.png" alt="logo de League of legend">
							</div>

							<div class="col-7 ">
								<div class="row">
									<?php
									echo '<h5 class="font-weight-bold mt-3">';
									echo 'Meilleur équipe sur League Of Legends :';
									echo '</h5>';
									?>

								</div>
								<div class="row">
									<?php
									echo '<h3 class="font-weight-bold mt-3" style="color:';
									echo $topTeam['color'];
									echo '">';
									echo $topTeam['name'];
									echo '</h3>';
									?>
								</div>
								<div class="row">
									<?php
									echo '<h4 class="font-weight-bold">';
									echo $topTeam['elo'].' d\'elo';
									echo '</h4>';
									?>
								</div>
							</div>

						</div>
					</div>

					<?php
					$queryPrepared->execute(["game"=>'Rocket League']);
					$topTeam = $queryPrepared->fetch(PDO::FETCH_ASSOC);
					?>

					<div class="carousel-item " data-interval="7000"><!-- Start of #2 carousel -->


						<div class="row ">

							<div class="col-2 ">
								<img src="Images/rocketLeagueLogo.png" width="150rem" alt="logo de Rocket League">
							</div>

							<div class="col-7">
								<div class="row">
									<?php
									echo '<h5 class="font-weight-bold mt-3">';
									echo 'Meilleur équipe sur Rocket League :';
									echo '</h5>';
									?>

								</div>
								<div class="row">
									<?php
									echo '<h3 class="font-weight-bold mt-3" style="color:';
									echo $topTeam['color'];
									echo '">';
									echo $topTeam['name'];
									echo '</h3>';
									?>
								</div>
								<div class="row">
									<?php
									echo '<h4 class="font-weight-bold">';
									echo $topTeam['elo'].' d\'elo';
									echo '</h4>';
									?>
								</div>
							</div>

						</div>


					</div>

					<?php
					$queryPrepared->execute(["game"=>'Super Smash Bros']);
					$topTeam = $queryPrepared->fetch(PDO::FETCH_ASSOC);
					?>

					<div class=" carousel-item " data-interval="7000"> <!-- Start of #3 carousel -->


						<div class="row ">

							<div class="col-2 ">
								<img src="Images/SSB.png" width="130rem" alt="logo de SSB">
							</div>

							<div class="col-7 ">
								<div class="row">
									<?php
									echo '<h5 class="font-weight-bold mt-3">';
									echo 'Meilleur équipe sur Super Smash Bros :';
									echo '</h5>';
									?>

								</div>
								<div class="row">
									<?php
									echo '<h3 class="font-weight-bold mt-3" style="color:';
									echo $topTeam['color'];
									echo '">';
									echo $topTeam['name'];
									echo '</h3>';
									?>
								</div>
								<div class="row">
									<?php
									echo '<h4 class="font-weight-bold">';
									echo $topTeam['elo'].' d\'elo';
									echo '</h4>';
									?>
								</div>
							</div>
						</div>

					</div>


		 </div>
		</div>
	</div>
 </div>
</section>

<!--Carte de jeux-->

							<section class="pricing py-5 "  >
								<div class="box2" >
									<div class="container ">
										<div class="row" id="game">


											<!-- League of legend -->
											<div class="col-lg-4 " >
												<div class="card mb-5 mb-lg-0 ">
													<div class="card-body">
														<h5  class="card-title text-muted text-uppercase text-center" >League of legend</h5>
														<div class="row">
															<div class="imgCenter">
																<img src="Images/leagueoflegends.png" alt="logo de League of legend" class="mb-4">
															</div>
														</div>
														<hr>
														<p class="px-2 "><b>Marre des clashs annulés toutes les deux semaines ?</b></p>
														<p>Cette fois, pas besoin de plusieurs millions pour créer ton équipe.  </p>
														<a href="newTeam.php">
															<button class="btn btn-block btn-primary text-uppercase">Crée ton équipe</button>
														</a>
													</div>
												</div>
											</div>

											<!-- Rocket League -->
											<div class="col-lg-4">
												<div class="card mb-5 mb-lg-0">
													<div class="card-body">
														<h5 class="card-title text-muted text-uppercase text-center">Rocket League</h5>
														<div class="row">
															<div class="imgCenter mb-2 mt-4">
																<img src="Images/rocketLeagueLogo.png" width="165rem" alt="Logo de Rocketleague">
															</div>
														</div>
														<hr>
														<p class="px-2 "><b>Marre des tournois à deux heures du mat’ ?</b></p>
														<p>Deviens le kingdop des flip reset et crée ton équipe maintenant !</p>
														<a href="newTeam.php">
															<button class="btn btn-block btn-primary text-uppercase">Créer une équipe</button>
														</a>
													</div>
												</div>
											</div>


											<!-- Smash bros-->
											<div class="col-lg-4">
												<div class="card">
													<div class="card-body">
														<h5 class="card-title text-muted text-uppercase text-center" >Smash Bros</h5>
														<div class="row">
															<div class="imgCenter mt-4">
																<img src="Images/SSB.png" alt="Logo de Smash Bros">
															</div>
														</div>
														<hr>
														<p class="px-2 "><b>Marre des smurfs, des Joker abuse et des toxic player ?</b></p>
														<p>Défini tes propres règles et crée ton équipe maintenant !  </p>
														<a href="newTeam.php">
															<button class="btn btn-block btn-primary text-uppercase">Créer une équipe</button>
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>



								<?php
									include "footer.php";
								?>
