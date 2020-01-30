<?php 

require "Connessione.php";
header("Access-Control-Allow-Origin: *");
$preparazioneConversioneImmagine = "https://api.cloudconvert.com/v1/process";


$apiKey = "DBoE1fW6G8G81qMqj3iwDObVfO0iclHKPYx3bNFKiuMfnicW-G-hrjn9GxbMnEEe3fPaVVTi91YEXkhXESt_gw";

move_uploaded_file($filePDF, "../Riassunti/".$_FILES['pdfDaCaricare']['name']);

$paramentri = "apikey=". $apiKey ."&inputformat=pdf&outputformat=jpg";

/*

PREPARAZIONE

*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $preparazioneConversioneImmagine);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $paramentri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$rispostaServer = curl_exec($ch);
//$rispostaServer = '{"url":"\/\/monique.infra.cloudconvert.com\/process\/2e63a4df-6972-4d8c-840b-f46f73473ef8","id":"2e63a4df-6972-4d8c-840b-f46f73473ef8","host":"monique.infra.cloudconvert.com","expires":"2020-01-30 04:20:39","maxsize":1024,"maxtime":1500,"concurrent":5}';
$rispostaServer = json_decode($rispostaServer, true);
$host = $rispostaServer["host"];
$url = "https:" . $rispostaServer["url"];
curl_close ($ch);
$ch = null;

/*

FINE PREPARAZIONE

*/

/*

INIZIO CONVERSIONE

*/

$parametriConversione = array();
$parametriConversione['input'] = "download";
$parametriConversione['file'] = "https://" . $_SERVER['HTTP_HOST']. "/Riassunti/".$_FILES['pdfDaCaricare']['name'];
//$parametriConversione['file'] = urlencode($parametriConversione['file']);
$parametriConversione['outputformat'] = "jpg";
$parametriConversione['wait'] = "true";

$parametriConversione = http_build_query($parametriConversione);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $parametriConversione);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$rispostaServer = curl_exec($ch);

$e1 = curl_error($ch); 
$e2 = curl_errno($ch);
$rispostaServer = json_decode($rispostaServer, true);
$url = "https:" . $rispostaServer['output']['url']. "/" . $rispostaServer['output']['files'][0];
curl_close ($ch);
$ch = null;

$ch = curl_init($url); 

$outputImmagine = "../Immagini/".$_FILES['pdfDaCaricare']['name']; 

$fp = fopen($outputImmagine, 'wb'); 

curl_setopt($ch, CURLOPT_FILE, $fp); 
curl_setopt($ch, CURLOPT_HEADER, 0); 

curl_exec($ch); 
curl_close($ch); 
fclose($fp); 

$connessione = new Connessione();
$filePDF = $_FILES['pdfDaCaricare']['tmp_name'];
$fileImg = $_FILES['fotoDaCaricare']['tmp_name'];

move_uploaded_file($fileImg, "../Immagini/".$_FILES['fotoDaCaricare']['name']);

$connessione->inserisci($_FILES['pdfDaCaricare']['name'], $_FILES['fotoDaCaricare']['name'], $_POST['indirizzi'], $_POST['materie'], $_POST['anno']);


?>