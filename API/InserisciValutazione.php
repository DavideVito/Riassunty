<?php
  header("Access-Control-Allow-Origin: *");
  header("content-type: application/json");
$valutazione = $_GET['valutazione'];
$idUtente = $_GET['idUtente'];
$idRiassunto = $_GET['idRiassunto'];

require "Connessione.php";

$connessione = new Connessione();

$esito = $connessione->inserisciValutazione($idUtente, $valutazione, $idRiassunto);
if($esito === false)
{
    $esito = $connessione->modificaValutazione($idUtente, $valutazione, $idRiassunto);
}
$ris = $connessione->mostraValutazione($idRiassunto);
die(json_encode($ris));
