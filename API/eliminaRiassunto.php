<?php 
header("Access-Control-Allow-Origin: *");
    $id = $_POST['id'];

    require_once "Connessione.php";

    $connessione = new Connessione();

    $connessione->rimuoviRiassunto($id);

?> 