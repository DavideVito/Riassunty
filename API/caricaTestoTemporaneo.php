<?php 
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");
require "Connessione.php";
try {
    $connessione = new Connessione();

    $nome = $_POST['nome'];
    $contenuto = $_POST['contenuto'];
    $idUtente = $connessione->controllaValidita($_POST['token'])['b'];
    $idRiassunto = "";
    if($_POST['idRiassunto'] !== "no" || !isset($_POST['idRiassunto']))
    {
        $idRiassunto = $_POST['idRiassunto'];
    }
    else
    {
        $idRiassunto = Connessione::generateToken();
        $connessione->inserisciRiassutoTemporaneo($idRiassunto, $idUtente, $nome);
    }
    
    $idFile = Connessione::generaCodiceRiassunto();

    $posizione = "../RiassuntiTemporanei/" . $nome . "." . $idRiassunto . "." . $idFile . ".html";

    $connessione->creaNuovaVersione($idFile, $idRiassunto, $posizione);

    
    
    $file = fopen($posizione, "w");

    fwrite($file, $contenuto);

    

    $ris['idRiassunto'] = $idRiassunto;

    die(json_encode($ris));
} catch (\Throwable $th) {
    echo json_encode($th);
}

