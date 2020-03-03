<?php
require "Connessione.php";    header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
$connessione = new Connessione();
if(!isset($_POST['token']))
{
    $arr['shouldRedirect'] = "true";
        echo json_encode($arr);
        die();
}

$token = $connessione->controllaValidita($_POST['token']);
if($token === null)
{
    $arr['shouldRedirect'] = "true";
        echo json_encode($arr);
        die();
}

$idUtente = $token['b'];

$filePDF = $_FILES['pdfDaCaricare']['tmp_name'];

if($filePDF === NULL)
{
    echo "No file PDF";
    die();
}

$hashFile = hash_file("sha512",  $_FILES['pdfDaCaricare']['tmp_name']);

$fileConEstensione = utf8_encode($_FILES['pdfDaCaricare']['name']);

$nomeFile = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileConEstensione);
$nomeFile = htmlspecialchars(urldecode($nomeFile));
$tmp = addcslashes($fileConEstensione, ' ');
$secondArg = $tmp . ".SHA512=$hashFile";



move_uploaded_file($filePDF, "../Riassunti/".$secondArg);


$escapedComandoShell = escapeshellarg($secondArg);


$comando = "pdf2htmlEX --dest-dir ../Riassunti/ ../Riassunti/".$escapedComandoShell;
$a = null;
$a = exec($comando . " 2>&1", $a);

$fileImmagine = $nomeFile.".png";

$im = new Imagick();
$im->setResolution(300, 300);     
$im->readImage("../Riassunti/" .$tmp. ".SHA512=$hashFile" ."[0]");    
$im->setImageFormat('png');
$im->writeImage("../Immagini/" . $tmp . ".SHA512=$hashFile.png"); 
unlink("../Riassunti/". $secondArg. ".SHA512=$hashFile");

$esito = $connessione->inserisci($nomeFile, $tmp ,".SHA512=$hashFile", $tmp, $_POST['indirizzi'], $_POST['materie'], $_POST['anno'], $idUtente);

if($esito === true && strcmp($a, "") === 0)
{
    echo "OK";
    die();
    
}
if($esito == 23000)
{
    echo "NOOK";
    die();
}

?>
