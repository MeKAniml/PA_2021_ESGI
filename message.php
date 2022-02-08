<?php

session_start();

require "functions.php";


/**
 * Analayse de la demande faite via l'URL GET pour déterminer si on récupére un message ou si on en écris un
 */




if (array_key_exists("task", $_GET)){
    $task = $_GET['task'];
}

if(isset($task)&&$task == "write"){
    postMessage();
} else {
    getMessages();
}
