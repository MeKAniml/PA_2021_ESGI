<?php
	include "header.php";

	//permet juste d'eviter d'acceder au captcha sans passer par login
if (!empty($_GET["login"])&&!empty($_GET["pwd"])) {

//permet de randomiser le set d'image utiliser entre 1, et X + indiquer son emplacement

$IT = "Images/captcha/".rand(1,3)."/";

//methode pour transferer l'emplacement des images utilisé au js

echo "<input id='rdm' type='hidden'  value='".$IT."'>";



echo "


<div class='container'>
	<div class='row'>

		<div class='box shadow border col-md-4 p-3 my-5'>

			<h1 class='text-green' style='font-family: Roboto' > PLAY </h1>
			<h4 class='font-weight-bolder'> Captcha </h4>

			<div class='row justify-content-center'>


<table  id='captcha' BORDER=0 CELLSPACING=0 >
<style type='text/css'>
table {
  border-collapse: collapse;
  border-spacing: 0px;
}
</style>
<tr>


    <td onclick=Swaps(1);>
      <img id=1 src='".$IT."1.png' width='100',
      height='auto'>
    </td>
    <td onclick=Swaps(2)>
      <img id=2 src='".$IT."2.png' width='100',
      height='auto'>
    </td>
    <td onclick=Swaps(3)>
      <img id=3 src='".$IT."3.png' width='100',
      height='auto'>
    </td>
    </tr>


    <tr>

        <td onclick=Swaps(4)>
          <img id=4 src='".$IT."4.png' width='100',
          height='auto'>

        <td onclick=Swaps(5)>
          <img id=5 src='".$IT."5.png' width='100',
          height='auto'>
        </td>

        <td onclick=Swaps(6)>
          <img id=6 src='".$IT."6.png' width='100',
          height='auto'>
        </td>

        </tr>


    <tr>

          <td onclick=Swaps(7)>
            <img id=7 src='".$IT."7.png' width='100',
            height='auto'>

          <td onclick=Swaps(8)>
            <img id=8 src='".$IT."8.png' width='100',
            height='auto'>
          </td>

          <td onclick=Swaps(9)>
            <img id=9 src='".$IT."9.png' width='100',
            height='auto'>
          </td>

    </tr>
  </table>
	</div>
    <button onclick='verif()' class='btn btn-primary mt-2'>Valider</button>
    <script onload='init()' src='CSS/bootstrap-4.6.0-dist/js/captcha.js'></script>
		</div>
		</div>
		</div>";

}else {

	header('Location: login.php');
	session_destroy();

}
	include "footer.php";
?>
