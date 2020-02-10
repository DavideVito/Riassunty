<?php 
    session_start();
    
    require "Connessione.php";    
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");

    $connessione = new Connessione();

    $idRiassunto = $_POST['id'];

    $approvatore = $_SESSION['ID'];

    

    $anteprima = $connessione->approvaRiassunto($idRiassunto, $approvatore);

    echo json_encode($ris);

?>