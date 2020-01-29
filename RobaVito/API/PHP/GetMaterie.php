<?php 

    require "Connessione.php";

    $conn = new Connessione();

    $daMandare = $conn->getMaterie($_POST['id']);

    echo json_encode($daMandare);

?>