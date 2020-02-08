<?php 
    header("Access-Control-Allow-Origin: *");
    require_once "Connessione.php";

    $connessione = new Connessione();

    $riassunti = $connessione->mostraRiassunto();

    //var_dump($riassunti);
    
    $tabella = "<center><br><br><br><table><tr><td>Immagine</td><td>Nome</td><td>Id</td><td>Data</td><td>Elimina</td></tr>";
    for($i = 0; $i < count($riassunti); $i++)
    {
        $riassunto = $riassunti[$i];
        //var_dump($riassunto);
        $titolo = $riassunto['Titolo'];
        $data = $riassunto['DataPubblicazione'];
        $id = $riassunto['IDRiassunto'];
        
        
        $immagineElimina = '<img src="https://img.icons8.com/cotton/2x/delete-sign--v2.png" onclick="eliminaRiassunto(' .addslashes(json_encode($riassunto, JSON_HEX_TAG)). '")>';
        $immagineRiassunto = '<img src="../' . $riassunto['UrlIMG'] . '">';
        $tabella .= "<tr><td>$immagineRiassunto</td><td>$titolo</td><td>$id</td><td>$data</td><td>$immagineElimina</td></tr>";
    }

    //var_dump($tabella);

    echo $tabella."</center>";
    
    ?> 