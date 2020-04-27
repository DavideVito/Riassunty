<?php 
 
header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
require "Connessione.php";
$connessione = new Connessione();

$token = json_decode($_POST['token'], true);

$risposta['esisteGia'] = true;

if(isset($_POST['token']))
{
    $myToken = $connessione->controllaValidita($token["id_token"], false)['a'];
    if($myToken !== null)
    {
        $risposta['token'] = $myToken;
        echo json_encode($risposta);
        die();
    }
}

$ris = $connessione->getUtente($_POST['id']);
if(count($ris) === 0)
{
    //username idGoogle Ruolo mail
    $ris = $connessione->creaUtente($_POST['username'], $_POST['idGoogle'], "Studente", $_POST['mail']);
}

//$token = Connessione::generateToken();
$idUtente = $ris[0]['IDUtente'];


$connessione->inserisciToken($idUtente, $token);

die(json_encode($ris));
?>