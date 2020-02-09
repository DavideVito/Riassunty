<?php 
    session_start();
    
    require "Connessione.php";    
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");

    $connessione = new Connessione();

    $idMateria = NULL;
    $anno = NULL;
    $proprietario = NULL;

    if(isset($_GET['idMateria']))
    {
        $idMateria = $_GET['idMateria'];
    }
    if(isset($_GET['anno']))
    {
        $anno = $_GET['anno'];
    }

    if(isset($_SESSION['ID']))
    {
        $proprietario = $_SESSION['ID'];
    }
    else
    {
        session_destroy();
    }

    $anteprima = $connessione->getRiassunto($idMateria, $anno, $proprietario);

    $ris = array();

    foreach($anteprima as $t)
    {
        $t2['ID'] = $t['IDRiassunto'];

        $t2['Titolo'] = preg_replace("/\.SHA512=\w{128}/m", "", $t['Titolo']);
        $t2['URLImmagine'] = $t['UrlIMG'];
        $t2['Valutazione'] = $t['Val'];
        $t2['DataPubblicazione'] = $t['DataPubblicazione'];
        array_push($ris, $t2);
    }

    echo json_encode($ris);

?>