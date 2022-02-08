<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require "conf.inc.php";


//fonction connexion BDD
function connectDB()
{

	try{

		$connection = new PDO(DBDRIVER.":host=".DBHOST.";port =".DBPORT.";dbname=".DBNAME , DBUSER, DBPWD, );
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	}catch(Exception $e){
		die("Erreur SQL : ". $e->getMessage());
	}


	return $connection;

}

//Fonction d'envoie d'image
function sendMail($email, $content){

	//Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = 2;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                 				    //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = 'play.kccorp@gmail.com';                   						  //SMTP username
		$mail->Password   = EMAILPWD;                              //SMTP password
		$mail->SMTPSecure = 'ssl';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		//Recipients
		$mail->setFrom('no-reply@play.fr', 'no-reply');
		$mail->addAddress($email);     //Add a recipient



		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		//$mail->addAttachment('Images\logo.svg', 'logo.svg');    //Optional name

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'Ton code de validation PLAY.fr';
		$mail->Body    = $content;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		echo 'Message has been sent';
	} catch (Exception $e) {
		//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}

}

function getMessages(){

		$connection = connectDB();

		// Requête de la base de donnés pour sortir les 20 derniers messages
		if (!empty($_SESSION['team']['id_matchs'])) {

		$resultats = $connection->prepare("SELECT author, content, creationTime, ".PRE."Team.color from ".PRE."Message JOIN ".PRE."Team on ".PRE."Team.id_team=".PRE."Message.team where ".PRE."Message.matchs=:matchs ORDER BY creationTime DESC ;");
		$resultats->execute(["matchs" => $_SESSION['team']['id_matchs']]);
		// Extraction des résultats
		$messages = $resultats->fetchAll(PDO::FETCH_ASSOC);
	}else {
		$resultats = $connection->prepare("SELECT author, content, creationTime, ".PRE."User.color from ".PRE."Message join ".PRE."User on ".PRE."Message.author=".PRE."User.username where ".PRE."Message.team=:team AND matchs=0 ORDER BY creationTime DESC ;");
		$resultats->execute(["team" => $_SESSION['team']['id_team']]);
		// Extraction des résultats
		$messages = $resultats->fetchAll(PDO::FETCH_ASSOC);
	}
		// Afficher les données au format JSON
		echo json_encode($messages);

}

function postMessage(){

		$connection = connectDB();

		//Analyse des paramètres passés en POST
		if (!array_key_exists('content',$_POST)){
				echo json_encode(["status" => "error", "message" => "Certains champs ne sont pas renseignés !"]);
				return;
		}else{
			if (!empty($_SESSION['team']['id_matchs'])) {

				$author = $_SESSION['info']['username'];
				$content = $_POST['content'];
				$match =$_SESSION['team']['id_matchs'];
				$team =$_SESSION['team']['id_team'];
				//Créer la requête qui permettra d'insérer ces données
				$query = $connection->prepare("INSERT INTO ".PRE."Message SET author = :author, content = :content, team = :team, matchs = :matchs, creationTime = NOW()");

				$query->execute([
						"author" => $author,
						"content" => $content,
						"matchs" => $match,
						"team" => $team
				]);

				//Donner statut de succes ou erreur au format JSON
				echo json_encode(["status" => "success"]);
			}else {

				$author = $_SESSION['info']['username'];
				$content = $_POST['content'];
				$team =$_SESSION['team']['id_team'];
				//Créer la requête qui permettra d'insérer ces données
				$query = $connection->prepare("INSERT INTO ".PRE."Message SET author = :author, content = :content, team = :team, creationTime = NOW()");

				$query->execute([
						"author" => $author,
						"content" => $content,
						"team" => $team
				]);

				//Donner statut de succes ou erreur au format JSON
				echo json_encode(["status" => "success"]);
			}
		}
}
