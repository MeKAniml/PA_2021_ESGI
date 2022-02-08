<?php include "header.php" ?>
<section>

  <hr class=" mt-5"/>
      <h1 class="text-center">GESTIONNAIRE D'ÉQUIPE</h1>
  <hr/>

  <?php
  if (isset($_SESSION["create team"]["name"]) && isset($_SESSION["create team"]["game"])) {
    if (count($_POST)==1 && !empty($_POST["color"])) {


      $_SESSION["create team"]["color"] = $_POST["color"];
      header("Location:Description.php");

    }
  } else {
    header("Location:index.php");
  }


   ?>

  <div class="container">
    <div class="row">
      <div class="col">
          <h3 class="pt-3 font-weight-bold text-center"> Choisisez la couleur de votre équipe</h3>
      </div>
    </div>
    <div class="row mt-4">

      <div class="col-8 text-center" id="changeColor"  style="color: #563d7c;">

        <svg width="143" height="101" viewBox="0 0 143 101" fill="none" xmlns="http://www.w3.org/2000/svg">
          <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="143" height="101">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.8889 0.404498C15.8889 9.3404 8.77519 16.5844 0 16.5844V100.315H143L143 16.5844C134.225 16.5844 127.111 9.34041 127.111 0.40451C127.111 0.269279 127.113 0.134436 127.116 0H15.884C15.8873 0.134432 15.8889 0.269272 15.8889 0.404498Z" fill="#ED2E7E"/>
          </mask>
          <g mask="url(#mask0)">

            <path id="pathblason"  style="fill:#563d7c;" d="M143 0H0V100.315H143V0Z" />
          </g>
        </svg>
      </div>
      <div class="col-4">
        <label for="exampleColorInput" class="form-label">Color picker</label>
        <input onchange="changeColor()" type="color" class="form-control form-control-color" id="colorPicker" value="#563d7c" title="Choose your color">
      </div>
    </div>
    <div class="row">
        <div class="col-8 text-center">
          <svg width="183" height="162" viewBox="0 0 183 162" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d)">
              <path d="M163 16.3152H20V97.059C20 103.254 23.5758 108.892 29.1795 111.532L77.8591 134.472C86.4975 138.543 96.5025 138.543 105.141 134.472L153.82 111.532C159.424 108.892 163 103.254 163 97.059V16.3152Z" fill="white"/>
            </g>

            <defs>
              <filter id="filter0_d" x="0" y="0.315186" width="183" height="161.21" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
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
      <div class="row">
        <div class="col-8">
          <a href="newTeam.php">
            <button type="button" name="button" class="btn btn-primary" href="Gestionnaired'equipe.php">Retour</button>
          </a>
        </div>
        <form method="post">

          <input  type="hidden" id="color" name="color" value="#563d7c"/>
          <div class="col-4 text-right">
              <button type="submit" class="btn btn-primary" href="Description.php">Suivant</button>
          </div>

        </form>

      </div>
    </div>
  </section>

  <script src="CSS\bootstrap-4.6.0-dist\js\Prod.js" charset="utf-8"></script>

<?php include "footer.php" ?>
