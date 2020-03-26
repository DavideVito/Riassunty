<?php 
header("Access-Control-Allow-Origin: *");
    require "Connessione.php";

    header("content-type: application/json");
    $connessione = new Connessione();
    
    echo json_encode($connessione->ottieniAnni());

?> 