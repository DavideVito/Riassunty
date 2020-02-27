<?php 

header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
require "Connessione.php";
$connessione = new Connessione();



$risposta['esisteGia'] = true;

if(isset($_POST['token']))
{
    $token = $connessione->controllaValidita($_POST['token'])['a'];
    if($token !== null)
    {
        $risposta['token'] = $token;
        echo json_encode($risposta);
        die();
    }
}

$ris = $connessione->getUtente($_POST['id']);


$token = Connessione::generateToken();
$idUtente = $ris[0]['IDUtente'];

$connessione->inserisciToken($idUtente, $token);

if(count($ris) == 0)
{
    $risposta['esisteGia'] = false;
}
$risposta['token'] = $token;
echo json_encode($risposta);

die();
?>