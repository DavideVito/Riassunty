<?php

require "Connessione.php";

$connessione = new Connessione();
header("Access-Control-Allow-Origin: *");
$preparazioneConversioneImmagine = "https://api.cloudconvert.com/v1/process";

$apiKey = "DBoE1fW6G8G81qMqj3iwDObVfO0iclHKPYx3bNFKiuMfnicW-G-hrjn9GxbMnEEe3fPaVVTi91YEXkhXESt_gw";

$paramentri = "apikey=". $apiKey ."&inputformat=pdf&outputformat=jpg";

var_dump($_FILES);
$filePDF = $_FILES['pdfDaCaricare']['tmp_name'];
move_uploaded_file($filePDF, "../Riassunti/".$_FILES['pdfDaCaricare']['name']);

$fileImmagine = $_FILES['pdfDaCaricare']['name'].".png";

echo "<br>../Riassunti/".$_FILES['pdfDaCaricare']['name'] . "<br> " . __DIR__;

$im = new Imagick();
$im->setResolution(300, 300);     //set the resolution of the resulting jpg
$im->readImage("../Riassunti/".$_FILES['pdfDaCaricare']['name'] ."[0]");    //[0] for the first page
$im->setImageFormat('png');

$im->writeImage("../Immagini/" . $fileImmagine); 

$connessione->inserisci($_FILES['pdfDaCaricare']['name'], $fileImmagine, $_POST['indirizzi'], $_POST['materie'], $_POST['anno']);



?>
