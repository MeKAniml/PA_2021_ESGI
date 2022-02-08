<?php
	include "header.php";
?>


<div class="container mt-5">

	<h1 class="text-center mb-5 display-4 font-weight-bold"><u>Mini-jeu : Memo</u></h1>

  <div class="row">
    <div class="col-8">
      <div id="resultat" class="text-center">
        <div>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
        </div>
        <div>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
        </div>
        <div>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
        </div>
        <div>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
          <button class="btn btn-primary m-1" disabled style="width:100px; height:100px; border-radius: 1;" >Afficher</button>
        </div>

      </div>
			<div id="Start" class="mt-5">
        <button type="button" class="btn btn-secondary" onclick="Start()">Commencer</button>
      </div>
    </div>

    <div class="col-4 text-center">
			<h2 class="text-center font-weight-bold">
				Leaderboard
			</h2>
      <table class="table table-dark" style="border-radius: 0px 0px 15px 15px; border-top : none;">
        <thead>
          <tr>
            <th scope="col">Pseudo</th>
            <th scope="col">Temps</th>
          </tr>
        </thead>
        <tbody id="leaderboard">
         <?php

         $queryPrepared = $connection->prepare("SELECT username, time FROM ".PRE."Memo ORDER BY time LIMIT 10;");
         $queryPrepared->execute();
         $leader = $queryPrepared->fetchALL(PDO::FETCH_ASSOC);

         foreach ($leader as $key=> $joueur) {

           echo '<tr>';
           echo   '<th scope="row">'.$leader[$key]["username"].'</th>';
           echo   '<td>'.$leader[$key]["time"].'</td>';
           echo '</tr>';

         }

          ?>
       </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-6">
      <div id="ici" class="form-group mt-5">
          <h2>Chonomètre :</h2>
          <h3 id="timer">00:00:0</h3>
      </div>
    </div>
  </div>

</div>



<script src="CSS\bootstrap-4.6.0-dist\js\mini-jeux.js" charset="utf-8"></script>
<?php
	include "footer.php";
?>
