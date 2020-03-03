<?php 
    session_start();
    
    require "Connessione.php";    
    header("Access-Control-Allow-Origin: *");
    header("content-type: application/json");

    $connessione = new Connessione();

    $idRiassunto = $_POST['id'];


    if(isset($_POST['token']))
    {
        $approvatore = $connessione->controllaValidita($_POST['token'])['b'];
        if($approvatore === null)
        {
            $arr['shouldRedirect'] = "true";
            echo json_encode($arr);
            die();
        }
    }
    else
    {
        $arr['shouldRedirect'] = "true";
        echo json_encode($arr);
        die();
    }

    

    $anteprima = $connessione->approvaRiassunto($idRiassunto, $approvatore);

    echo json_encode($ris);

?>