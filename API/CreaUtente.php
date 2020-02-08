<?php 
header("Access-Control-Allow-Origin: *");

require "Connessione.php";

$connessione = new Connessione();

$mail = $_POST['mail'];
$idGoogle = $_POST['idGoogle'];
$ruolo = $_POST['Ruolo'];
$username = $_POST['Username'];


$esito =  $connessione->creaUtente($username, $idGoogle, $ruolo, $mail);
var_dump($esito);


?>