<?php 
header("Access-Control-Allow-Origin: *");
    require "Connessione.php";    
    header("content-type: application/json; charset=utf-8");

    $connessione = new Connessione();

    $id = NULL;
    $nome = NULL;

    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
    }
    
    $anteprima = $connessione->mostraRiassunto($id);
    $ris = array();    
    

    foreach($anteprima as $t)
    {
        $t2['Titolo'] =  htmlspecialchars($t['Titolo']);
        //$t2['URLPdf'] = $t['UrlPDF'];
        $file = file_get_contents("../".$t['UrlPDF']);
        $file = base64_encode($file);
        //$t2['url'] = "https://vps.lellovitiello.tk/Riassunt
        $t2['txt'] =   $file; //"data:application/pdf;base64," .
        array_push($ris, $t2);
    }


    echo json_encode($ris);
?>