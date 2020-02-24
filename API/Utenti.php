<?php 
session_start();

$a = headers_list();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

$a = headers_list();

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
    $_SESSION['Tipo'] = $ris[0]['Ruolo'];
}


echo json_encode($risposta);
?>