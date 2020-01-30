<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1><?php 
    
        require "../API/PHP/Connessione.php";

        $connessione = new Connessione();

        $riassunto = $connessione->getRiassunto($_GET['id']);

        echo "<h1>" . $riassunto['Nome'] . "</h1>";
    ?></h1>
    <object 
        style="width: 100%; height: 1000px;" 
        type="application/pdf" 
        data="
            <?php 

                $file = file_get_contents("../".$riassunto['Posizione']);
                $base64 = base64_encode($file);

                echo "data:application/pdf;base64,".$base64;
            ?>">
    </object>


</body>
</html>