<?php 

    
	require "Connessione.php";

    $connessione = new Connessione();
	$daMandare = $connessione->getAnni($_GET['id']);
    var_dump($daMandare);
    $daMandare = json_encode($daMandare, JSON_UNESCAPED_UNICODE);
	
    
    echo $daMandare ;

?>