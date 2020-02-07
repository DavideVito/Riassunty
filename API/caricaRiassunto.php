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
$cloudConvert = new CloudConvert();

$fileConEstensione = utf8_encode($_FILES['pdfDaCaricare']['name']);
$nomeFile = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileConEstensione);

move_uploaded_file($filePDF, "../Riassunti/".$fileConEstensione);

$linkFile = "https://" . $_SERVER["HTTP_HOST"] . "/Riassunty/Riassunti/" . $fileConEstensione;

echo $linkFile . "<br>";
echo $fileConEstensione . "<br>";
echo $nomeFile . "<br>";
echo "../Riassunti/". $fileConEstensione . "<br>";

$urlConversione = $cloudConvert->preparaConversione();

$urlFile = $cloudConvert->iniziaConversione($urlConversione, $linkFile);

$cloudConvert->salvaFile($urlFile, "../Riassunti/".$nomeFile.".html" );

$fileImmagine = $nomeFile.".png";

$im = new Imagick();
$im->setResolution(300, 300);     //set the resolution of the resulting jpg
$im->readImage("../Riassunti/".$fileConEstensione ."[0]");    //[0] for the first page
$im->setImageFormat('png');

$im->writeImage("../Immagini/" . $fileImmagine); 
unlink("../Riassunti/". $fileConEstensione);

$connessione->inserisci($nomeFile . ".html", $fileImmagine, $_POST['indirizzi'], $_POST['materie'], $_POST['anno']);



?>
