<?php 
header("Access-Control-Allow-Origin: *");
    $id = $_POST['id'];

    echo json_encode($_POST);
    require_once "Connessione.php";

    $connessione = new Connessione();

    $connessione->rimuoviRiassunto($id);

?> 