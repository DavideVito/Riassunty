<?php
session_start();
if(!isset($_SESSION['ID']))
{
    echo "<script>window.location = '../Login.html'</script>";die();
}
header("Access-Control-Allow-Origin: *");

require "Connessione.php";

$connessione = new Connessione();
/*
var_dump($_FILES);
*/
$filePDF = $_FILES['pdfDaCaricare']['tmp_name'];

if($filePDF === NULL)
{
    echo "No file PDF";
    die();
}

$hashFile = hash_file("sha512",  $_FILES['pdfDaCaricare']['tmp_name']);

$fileConEstensione = utf8_encode($_FILES['pdfDaCaricare']['name']);
$nomeFile = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileConEstensione);
/*
echo "<br><br><h1>$fileConEstensione</h1><br><br>";*/
move_uploaded_file($filePDF, "../Riassunti/".$fileConEstensione. ".SHA512=$hashFile");



$secondArg = escapeshellarg($fileConEstensione) . ".SHA512=$hashFile";
/*
echo "Secondo argomento: $secondArg<br>";

echo "File input pdf2htmlEX: ../Riassunti/".$fileConEstensione. ".SHA512=$hashFile" ."<br>";*/
$comando = "pdf2htmlEX --dest-dir ../Riassunti/ ../Riassunti/".$fileConEstensione. ".SHA512=$hashFile";

$a = null;
$a = exec($comando . " 2>&1", $a);


/*


echo "<br>" . $hashFile . "<br>";
echo $fileConEstensione . "<br>";
echo $nomeFile . "<br>";
echo "../Riassunti/". $fileConEstensione . "<br>";
*/
$fileImmagine = $nomeFile.".png";

$im = new Imagick();
$im->setResolution(300, 300);     //set the resolution of the resulting jpg
$im->readImage("../Riassunti/" .$fileConEstensione. ".SHA512=$hashFile" ."[0]");    //[0] for the first page
$im->setImageFormat('png');
/*echo "Scrivo la foto<br><br>";*/
$im->writeImage("../Immagini/" . $fileImmagine . ".SHA512=$hashFile"); 
unlink("../Riassunti/". $fileConEstensione. ".SHA512=$hashFile");

$esito = $connessione->inserisci($fileConEstensione, ".SHA512=$hashFile", $fileImmagine, $_POST['indirizzi'], $_POST['materie'], $_POST['anno']);

if($esito === true && strcmp($a, "") === 0)
{
    echo "Hai caricato $nomeFile";
    
}
if($esito == 23000)
{
    echo "Questo file è gia stato caricato<br>";
    echo "E sia ben chiaro, quando dico questo file, intendo QUESTO FILE<br>";
    echo "2 file sono uguali quando hanno lo stesso nome, la stessa estensione, e lo stesso fingerprint, calcolato tramite l'algoritmo <a href='https://it.wikipedia.org/wiki/Secure_Hash_Algorithm'> SHA512</a><br>";
    echo "Nome e estensione possono anche essere uguali, ma lo SHA512 no, se vedi questo messaggio vuol dire che hai caricato un file che è gia stato caricato oppure hai rotto un algoritmo che non si puo rompere<br>";
    echo "Caricane un altro, se pensi ci sia un errore mandaci una mail<br>";
}
echo "<br> <a href='index.html'> Torna indietro"; 

?>
