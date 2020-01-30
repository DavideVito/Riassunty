<?php

    $id = $_GET['id'];

    require "Connessione.php";

    $connessione = new Connessione();

    $daMandare = $connessione->getIndirizzi($id);
    $daMandare = json_encode($daMandare);
    echo $daMandare ;

?>