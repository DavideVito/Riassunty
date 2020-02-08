<?php 
session_start();
header("Access-Control-Allow-Origin: *");

require "Connessione.php";

$connessione = new Connessione();
$ris = $connessione->getUtente($_POST['id']);


$risposta['esisteGia'] = true;

if(count($ris) == 0)
{
    $risposta['esisteGia'] = false;
}

if($risposta['esisteGia'])
{
    $_SESSION['ID'] = $ris[0]['IDUtente'];
}

echo json_encode($risposta);
?>