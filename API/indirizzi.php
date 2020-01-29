<?php 


    /*
        Se si ha questo parametro passato tramite get, allora si hanno tutti gli 
        indirizzi che hanno quella materia
    
    */
    $idMateria = "";
    header("content-type: application/json");
    if(isset($_GET['idMateria']))
    {
        $idMateria = $_GET['idMateria'];
    }
    
    require "Connessione.php";

    $connessione = new Connessione();
    $indirizzi = $connessione->getIndirizzi($idMateria);
    
    echo json_encode($indirizzi);

?>