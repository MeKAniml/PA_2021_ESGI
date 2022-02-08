<?php include 'header.php';
/*if (!isset ($_SESSION['info'])){
  $_SESSION["listOfErrors"] = "Veuillez vous connecter avant de créer une équipe";
	header('Location:login.php');
	exit;
}*/
$team = $_SESSION["team"];

  $connection = connectDB();
  $queryPrepared=$connection->prepare("SELECT ".PRE."Matchs.id_matchs,winner,honor, ".PRE."Team.name,game,color FROM ".PRE."TeamMatchs INNER JOIN ".PRE."Team ON ".PRE."TeamMatchs.Team = ".PRE."Team.id_team INNER JOIN ".PRE."Matchs ON ".PRE."TeamMatchs.Matchs = ".PRE."Matchs.id_matchs  WHERE ".PRE."Team.id_team = :id_team;");
  $queryPrepared->execute(["id_team"=>$team["id_team"]]);
  $matches=$queryPrepared->fetchALL(PDO::FETCH_ASSOC);

   ?>
   <hr class=" mt-5"/>
       <h1 class="text-center font-weight-bold ">Historique de <?php echo $team["name"]; ?> </h1>
   <hr/>
<?php
if (count($matches)==0) {

  echo '
  <div class="col-12 text-center mt-5">
    <h1><u><a class="text-black" href="EditTeam2.php?name='.$team["name"].'">Vous n\'avez pas encore fais de match  !</a></u></h1>
  </div>';

} else {

  foreach ($matches as $key => $value) {
    $id_matchs= $matches[$key]["id_matchs"];
    $id_team= $team["id_team"];

    $queryPrepared=$connection->prepare("SELECT ".PRE."Team.color AS 'vsColor' FROM ".PRE."Team INNER JOIN ".PRE."TeamMatchs ON ".PRE."TeamMatchs.Team=".PRE."Team.id_team WHERE ".PRE."TeamMatchs.Matchs=:id_matchs AND ".PRE."TeamMatchs.team != :id_team;");
    $queryPrepared->execute(["id_matchs"=>$id_matchs, "id_team"=>$id_team]);
    $vsColor=$queryPrepared->fetch(PDO::FETCH_ASSOC);


    echo '<div class="container">
      <div class="row my-5">
      <div class="col text-center">
      <svg width="200" height="46" viewBox="0 0 183 246" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g filter="url(#filter0_d)">
      <path d="M163 100.315H20V181.059C20 187.254 23.5758 192.892 29.1795 195.532L77.8591 218.472C86.4975 222.543 96.5025 222.543 105.141 218.472L153.82 195.532C159.424 192.892 163 187.254 163 181.059V100.315Z" fill="white"/>
      </g>
      <rect x="56" y="163" width="71.5" height="0.808994" fill="#E0E0E0"/>
      <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="20" y="0" width="143" height="101">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M35.8889 0.404498C35.8889 9.3404 28.7752 16.5844 20 16.5844V100.315H163L163 16.5844C154.225 16.5844 147.111 9.34041 147.111 0.40451C147.111 0.269279 147.113 0.134436 147.116 0H35.884C35.8873 0.134432 35.8889 0.269272 35.8889 0.404498Z" fill="#ED2E7E"/>
      </mask>
      <g mask="url(#mask0)">
      <path d="M163 0H20V100.315H163V0Z" fill="'.$matches[$key]["color"].'"/>;
      </g>
      <defs>
      <filter id="filter0_d" x="0" y="84.3152" width="183" height="161.21" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
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
      <div class="col-sm text-center">
      <svg width="40" height="85" viewBox="0 0 154 238" fill="none" xmlns="http://www.w3.org/2000/svg">
      <line x1="0.57993" y1="237.729" x2="153.58" y2="0.728809" stroke="black"/>
      <path d="M93.8763 65.432L73.5883 111H66.8683L46.5802 65.432H55.1562L70.3243 100.632L85.4923 65.432H93.8763ZM78.7583 191.576C75.2596 191.576 71.9743 191.107 68.9023 190.168C65.8729 189.229 63.2916 187.885 61.1583 186.136L63.9743 180.12C66.1929 181.784 68.4969 183.021 70.8863 183.832C73.3183 184.6 75.9636 184.984 78.8223 184.984C81.9796 184.984 84.4116 184.429 86.1183 183.32C87.8676 182.211 88.7423 180.653 88.7423 178.648C88.7423 176.941 87.9316 175.64 86.3103 174.744C84.7316 173.805 82.1076 172.931 78.4383 172.12C72.7209 170.883 68.5609 169.283 65.9583 167.32C63.3556 165.357 62.0543 162.499 62.0543 158.744C62.0543 156.056 62.7796 153.667 64.2303 151.576C65.6809 149.485 67.7289 147.843 70.3743 146.648C73.0623 145.453 76.1556 144.856 79.6543 144.856C82.8543 144.856 85.8836 145.347 88.7423 146.328C91.6436 147.267 94.0116 148.589 95.8463 150.296L93.0943 156.312C89.0409 153.069 84.5609 151.448 79.6543 151.448C76.7103 151.448 74.3849 152.067 72.6783 153.304C70.9716 154.499 70.1183 156.141 70.1183 158.232C70.1183 160.024 70.8649 161.389 72.3583 162.328C73.8943 163.267 76.4969 164.141 80.1663 164.952C83.9636 165.848 87.0569 166.829 89.4463 167.896C91.8356 168.92 93.6703 170.264 94.9503 171.928C96.2303 173.549 96.8703 175.597 96.8703 178.072C96.8703 180.803 96.1449 183.192 94.6943 185.24C93.2436 187.245 91.1529 188.803 88.4223 189.912C85.6916 191.021 82.4703 191.576 78.7583 191.576Z" fill="black"/>
      </svg>
      </div>
      <div class="col text-center">
      <svg width="200" height="46" viewBox="0 0 183 246" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g filter="url(#filter0_d)">
      <path d="M163 100.315H20V181.059C20 187.254 23.5758 192.892 29.1795 195.532L77.8591 218.472C86.4975 222.543 96.5025 222.543 105.141 218.472L153.82 195.532C159.424 192.892 163 187.254 163 181.059V100.315Z" fill="white"/>
      </g>
      <rect x="56" y="163" width="71.5" height="0.808994" fill="#E0E0E0"/>
      <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="20" y="0" width="143" height="101">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M35.8889 0.404498C35.8889 9.3404 28.7752 16.5844 20 16.5844V100.315H163L163 16.5844C154.225 16.5844 147.111 9.34041 147.111 0.40451C147.111 0.269279 147.113 0.134436 147.116 0H35.884C35.8873 0.134432 35.8889 0.269272 35.8889 0.404498Z" fill="#ED2E7E"/>
      </mask>
      <g mask="url(#mask0)">
      <path d="M163 0H20V100.315H163V0Z" fill="'.$vsColor['vsColor'].'"/>
      </g>
      <defs>
      <filter id="filter0_d" x="0" y="84.3152" width="183" height="161.21" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
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
      </div>
      <div class="container">
      <div class="row">
      <div class="col my-1 ml-5 card2 card-body justify-content-center">
      <form method="post">
      <ul class="cardScore text-center ">
      <li name="id_team"> <h4 class="mt-1">'.$matches[$key]["game"].'</h4> </li>
      <li class="font-weight-bold"> <h2>Le gagnant est :'.$matches[$key]["winner"].'</h2> </li>
      <li> <h4>Honor : '.$matches[$key]["honor"].'</h4> </li>
      </ul>
      </form>
      </div>
      </div>
      </div>
      </div>
      ';

  }
}

 ?>




<?php include 'footer.php'; ?>
