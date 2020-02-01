<?php 

    require "Connessione.php";    
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");

    $connessione = new Connessione();

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

    if(isset($_POST['idMateria']))
    {
        $idMateria = $_POST['idMateria'];
    }
    if(isset($_POST['anno']))
    {
        $anno = $_POST['anno'];
    }

    $anteprima = $connessione->getRiassunto($idMateria, $anno);

    $ris = array();

    foreach($anteprima as $t)
    {
        $t2['Titolo'] = $t['Titolo'];
        $t2['URLImmagine'] = $t['UrlIMG'];
        $ts['Valutazione'] = $t['Val'];
        array_push($ris, $t2);
    }

    echo json_encode($ris);

?>