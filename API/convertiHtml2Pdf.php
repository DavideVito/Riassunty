<?php 
require "Connessione.php";
header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
$idRiassunto = $_POST['idRiassunto'];
$idFile = $_POST['idFile'];




$connessione = new Connessione(); 

$riassunto = $connessione->getPosizioneFileTemporanero($idRiassunto, $idFile);

$posizione = $riassunto['Posizione'];
$nome = $riassunto['Nome'];

$idTmp = $connessione->generaCodiceRiassunto();

$stile = "<style>@importurl('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');h1{text-align:center;margin-top:50px;}*{font-family:'OpenSans',sans-serif;margin-left:20px;margin-right:20px;}</style>";

$posizioneTemporanea = "../tmpRiass/".$idTmp.".html";

$documentoTemp = fopen($posizioneTemporanea, "w");

fwrite($documentoTemp, $stile . file_get_contents($posizione));

$urlFilehtml = $_SERVER["SERVER_NAME"]  . $_SERVER["REQUEST_URI"] . "/../" . $posizioneTemporanea;

$escapedComandoShell = escapeshellarg($nome.".pdf");

$posizionePDF = "../Riassunti/" . $escapedComandoShell;
$comando = "/usr/local/bin/wkhtmltopdf " . $urlFilehtml . " ../Riassunti/" . $escapedComandoShell;

$a = null;
$a = shell_exec($comando . " 2>&1");

unlink($posizioneTemporanea);

$contenutiFile = file_get_contents("../Riassunti/" . $nome .".pdf");
$daRet['nome'] = $nome.".pdf";;
$daRet['base64'] = "data:application/pdf;base64," .base64_encode($contenutiFile);
unlink("../Riassunti/" . $nome .".pdf");

die(json_encode($daRet));