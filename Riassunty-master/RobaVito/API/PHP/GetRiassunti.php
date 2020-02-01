<?php 

require "Connessione.php";

$connessione = new Connessione();

$riassunti = $connessione->getRiassunti($_POST['id']);

$daMandare = json_encode($riassunti);

echo $daMandare;

?> 