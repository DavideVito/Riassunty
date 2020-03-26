<?php 

header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
require "Connessione.php";
$connessione = new Connessione();


$idUtente = $connessione->controllaValidita($_POST['token'])['b'];

$riassuntiTemporanei = $connessione->getRiassuntiTemporanei($idUtente);

die(json_encode($riassuntiTemporanei));