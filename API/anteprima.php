<?php 
    //session_start();
    
    require "Connessione.php";    
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");

    $connessione = new Connessione();

    $idMateria = NULL;
    $anno = NULL;
    $proprietario = NULL;
    $tipo = NULL;
    $nome = NULL;

    $obbligo = isset($_GET['normale']);


    $proprietario = $connessione->controllaValidita($_GET['token'], $obbligo)['b'];

    if(isset($_GET['idMateria']))
    {
        $idMateria = $_GET['idMateria'];
    }
    if(isset($_GET['anno']))
    {
        $anno = $_GET['anno'];
    }

    

    if(isset($_GET['nome']))
    {
        $nome = $_GET['nome'];
    }

    $tipo = $connessione->getUtente($proprietario, "normale")[0]['Ruolo'];
    
    if(isset($_GET['prendiProp']))
    {
        if(isset($_GET['token']))
        {
            $proprietario = $connessione->controllaValidita($_GET['token'])['b'];
        }
    }
    else
    {
        $proprietario = NULL;
    }
    

    $anteprima = $connessione->getRiassunto($idMateria, $anno, $proprietario, $tipo, $nome);

    $ris = array();

    foreach($anteprima as $t)
    {
        $t2['ID'] = $t['IDRiassunto'];

        $t2['Titolo'] = htmlspecialchars($t['Titolo']);
        $t2['URLImmagine'] = rawurlencode($t['UrlIMG']);
        $t2['Valutazione'] = htmlspecialchars($t['Val']);
        $t2['DataPubblicazione'] = $t['DataPubblicazione'];
        array_push($ris, $t2);
    }

    echo json_encode($ris);

?>