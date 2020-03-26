<?php 
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");
require "Connessione.php";
$connessione = new Connessione();
$valido = $connessione->controllaValidita($_POST['token']);
$valido['valido'] = ($valido === null) ? false : true;

echo json_encode($valido);

?>