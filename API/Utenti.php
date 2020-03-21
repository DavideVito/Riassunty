<?php 
 
header("Access-Control-Allow-Origin: *");
header("content-type: application/json");
require "Connessione.php";
$connessione = new Connessione();



$risposta['esisteGia'] = true;

if(isset($_POST['token']))
{
    $token = $connessione->controllaValidita($_POST['token'], false)['a'];
    if($token !== null)
    {
        $risposta['token'] = $token;
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

$token = Connessione::generateToken();
$idUtente = $ris;


$connessione->inserisciToken($idUtente, $token);

$risposta['token'] = $token;
echo json_encode($risposta);

die();
?>