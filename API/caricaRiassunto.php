<?php
header("Access-Control-Allow-Origin: *");

require "Connessione.php";
require "CloudConvert.php";

$connessione = new Connessione();

var_dump($_FILES);

$filePDF = $_FILES['pdfDaCaricare']['tmp_name'];

if($filePDF === NULL)
{
    die();
}

$fileConEstensione = utf8_encode($_FILES['pdfDaCaricare']['name']);
$nomeFile = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileConEstensione);

echo "<br><br><h1>$fileConEstensione</h1><br><br>";
move_uploaded_file($filePDF, "../Riassunti/".$fileConEstensione);

$comando = "pdf2htmlEX --dest-dir ../Riassunti/ ../Riassunti/".escapeshellarg($fileConEstensione);

echo "<br>$comando<br>";

$a = null;
$a = exec($comando . " 2>&1", $a);

var_dump($a);

/*echo $linkFile . "<br>";*/
echo $fileConEstensione . "<br>";
echo $nomeFile . "<br>";
echo "../Riassunti/". $fileConEstensione . "<br>";

$fileImmagine = $nomeFile.".png";

$im = new Imagick();
$im->setResolution(300, 300);     //set the resolution of the resulting jpg
$im->readImage("../Riassunti/".$fileConEstensione ."[0]");    //[0] for the first page
$im->setImageFormat('png');

$im->writeImage("../Immagini/" . $fileImmagine); 
unlink("../Riassunti/". $fileConEstensione);

$connessione->inserisci($nomeFile . ".html", $fileImmagine, $_POST['indirizzi'], $_POST['materie'], $_POST['anno']);



?>
