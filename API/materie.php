<?php 
header("Access-Control-Allow-Origin: *");
    $nomeMaterie = "";
    header("content-type: application/json");
    if(isset($_POST['indirizzo']))
    {
        $nomeMaterie = $_POST['indirizzo'];
    }
    
    if(isset($_GET['indirizzo']))
    {
        $nomeMaterie = $_GET['indirizzo'];
    }
    
    require "Connessione.php";

    $connessione = new Connessione();

    $materie = $connessione->getMaterie($nomeMaterie);

    echo json_encode($materie);

?>