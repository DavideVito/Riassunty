<?php 
    session_start();
    $proprietario = NULL;
    require "Connessione.php";    
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    header("content-type: application/json");

    $connessione = new Connessione();

    $proprietario = $connessione->controllaValidita($_GET['token'])['b'];
    if($proprietario === null)
    {
        $arr['shouldRedirect'] = "true";
        echo json_encode($arr);
        die();
    }

    $idMateria = NULL;
    $anno = NULL;
    

    if(isset($_GET['idMateria']))
    {
        $idMateria = $_GET['idMateria'];
    }
    if(isset($_GET['anno']))
    {
        $anno = $_GET['anno'];
    }
    $tipo = "Studente";
    if(isset($_GET['token']))
    {
        $tipo = $connessione->getUtente($proprietario, "normale")[0]['Ruolo'];
        $proprietario = NULL;
    }
    
     
    



    $anteprima = $connessione->getRiassuntiNonApprovati($idMateria, $anno, $proprietario, $tipo);

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