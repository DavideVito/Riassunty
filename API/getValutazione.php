<?php
  header("Access-Control-Allow-Origin: *");
  header("content-type: application/json");
$idRiassunto = $_GET['idRiassunto'];
require "Connessione.php";
$connessione = new Connessione();
$ris = $connessione->mostraValutazione($idRiassunto);
if($ris === null)
{
    $ris['IDRiassunto'] = $idRiassunto;
    $ris['Valutazione'] = 0;
}
die(json_encode($ris));
