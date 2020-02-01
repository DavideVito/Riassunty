<?php 
header("Access-Control-Allow-Origin: *");
    require "Connessione.php";    
    header("content-type: application/json");

    $connessione = new Connessione();

    $nome = NULL;

    if(isset($_GET['nome']))
    {
        $nome = $_GET['nome'];
    }
    
    $anteprima = $connessione->mostraRiassunto($nome);
    
    $ris = array();    

    foreach($anteprima as $t)
    {
        $t2['Titolo'] = $t['Titolo'];
        $t2['URLPdf'] = $_SERVER['SERVER_NAME']."/".$t['UrlPDF'];
        
        $file = file_get_contents("../".$t['UrlPDF']);
        
        $t2['base64'] = "data:application/pdf;base64,". base64_encode($file);
        array_push($ris, $t2);
    }


    echo json_encode($ris);
?>