<?php 

header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
require "Connessione.php";
$connessione = new Connessione();

$idFile = $_POST['file'];
$idRiassunto = $_POST['riassunto'];

$posizioneFile = $connessione->getPosizioneFileTemporanero($idRiassunto, $idFile);


$contenuto = file_get_contents($posizioneFile['Posizione']);

$tmp['txt'] = $contenuto;

die(json_encode($tmp)); 