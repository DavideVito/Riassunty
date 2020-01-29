<?php

    require "Connessione.php";

    $connessione = new Connessione();
    $daMandare = $connessione->getScuole();
    $daMandare = json_encode($daMandare);
    echo $daMandare ;

?>