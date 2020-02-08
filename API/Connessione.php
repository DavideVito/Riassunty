<?php

class Connessione {

    private $connessione = null;

    public function disconnetti() {
        $this->connessione = null;
    }

    public function __construct() {
        try {
            $host = "127.0.0.1";
            $dataBase = "my_riassunty";
            //$uid = "riassunty";
            //$pwd = "";

            $uid = "Admin";
            $pwd = "Password";

            $this->connessione = new PDO("mysql:host=$host;dbname=$dataBase", $uid, $pwd);
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            die(); // Lo script termina
        }
    }

    public function getMaterie($di) {
        
        $sql = "SELECT * FROM Materie where Indirizzo like :di";
        $di = "%".$di."%";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":di", $di, PDO::PARAM_STR);
        $esito = $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function getIndirizzi($idMateria)
    {
        $sql = "SELECT distinct Indirizzo from Materie where 1";
        
        if($idMateria !== "")
        {
            $sql .= "and IDMateria = :id";
        }

        $stm = $this->connessione->prepare($sql);
        if($idMateria === "")
        {
            $stm->bindParam(":id", $idMateria, PDO::PARAM_INT);  
        }
         
        $esito = $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

	public function mostraRiassunto($nome = NULL)
    {
    	$sql = "SELECT * FROM Riassunti WHERE 1 ";
        if($nome !== NULL)
        {
            $sql .= " and Titolo = :nome ";
        }
        $stm = $this->connessione->prepare($sql);
        if($nome !== NULL)
        {
            $stm->bindParam(":nome", $nome, PDO::PARAM_STR);
        }

        $esito = $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);   
    }

    public function rimuoviRiassunto($id)
    {
        $sql = "SELECT * FROM Riassunti WHERE Riassunti.IDRiassunto = :id";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $id, PDO::PARAM_INT);

        $stm->execute();

        $ris = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
        
        $posizionePDF = "../".$ris['UrlPDF'];
        $posizioneIMG = "../".$ris['UrlIMG'];
        

        unlink($posizionePDF);
        unlink($posizioneIMG);

        $sql = "DELETE FROM `my_riassunty`.`Riassunti` WHERE `Riassunti`.`IDRiassunto` = :id";

        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $id, PDO::PARAM_INT);
        $stm->execute();
        
        var_dump($stm->errorInfo());
        
        
        die();
        
        
    }
    

    public function getRiassunto($idMateria, $anno)
    {

        
	//SELECT *, AVG(Valutazione) FROM Riassunti LEFT JOIN Valutazioni using(IDRiassunto) GROUP BY Riassunti.IDRiassunto
        $sql = "select `Riassunti`.`IDRiassunto` AS `IDRiassunto`,`Riassunti`.`Titolo` AS `Titolo`,`Riassunti`.`UrlPDF` AS `UrlPDF`,`Riassunti`.`UrlIMG` AS `UrlIMG`,`Riassunti`.`IDMateria` AS `IDMateria`,`Riassunti`.`Anno` AS `Anno`,`Riassunti`.`DataPubblicazione` AS `DataPubblicazione`,avg(`Valutazioni`.`Valutazione`) AS `Val` from (`Riassunti` left join `Valutazioni` on((`Riassunti`.`IDRiassunto` = `Valutazioni`.`IDRiassunto`))) where 1"; 
        //$sql = "SELECT * FROM `v_riassunti` where 1";
        if($idMateria !== NULL)
        {
            $sql .= " and IDMateria = :id ";
        }
       
        if($anno !== NULL)
        {
            $sql .= "and Anno = :anno ";
        }

        
        $sql .= " group by `Riassunti`.`IDRiassunto`,`Riassunti`.`Titolo`,`Riassunti`.`UrlPDF`,`Riassunti`.`UrlIMG`,`Riassunti`.`Anno`,`Riassunti`.`IDMateria`,`Riassunti`.`DataPubblicazione` order by `Val`,`Riassunti`.`DataPubblicazione` desc";

        $sql .= "";
        $stm = $this->connessione->prepare($sql);
        if($idMateria !== NULL)
        {
            $stm->bindParam(":id", $idMateria, PDO::PARAM_INT);
        }

        if($anno !== NULL)
        {
            $stm->bindParam(":anno", $anno, PDO::PARAM_STR);
        }

        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }
       
        return $stm->fetchAll(PDO::FETCH_ASSOC);   
    }

    public function inserisci($pdf, $immagine, $indirizzo, $matiera, $anno)
    {
        $sql = "INSERT INTO `Riassunti`(`Titolo`, `IDUtente`, `UrlPDF`, `UrlIMG`, `IDMateria`, `Anno`) VALUES (:titolo, :IDUtente ,:urlP, :urlI, :idMateria, :anno); ";
       
        //:titolo, :urlP, :urlI, :idMateria, :anno, null
        $baseUrlRiass = "Riassunti/";
        $baseUrlImage = 'Immagini/';

        $urlImage = $baseUrlImage . $immagine;
        $urlPdf = $baseUrlRiass . $pdf;
        $stm = $this->connessione->prepare($sql);

        var_dump($pdf);
        var_dump($immagine);
        var_dump($indirizzo);
        var_dump($matiera);
        var_dump($anno);
        var_dump($_SESSION);

        $stm->bindParam(":titolo", $pdf, PDO::PARAM_STR);
        $stm->bindParam(":IDUtente", $_SESSION['ID'], PDO::PARAM_INT);
        $stm->bindParam(":urlP", $urlPdf, PDO::PARAM_STR);
        $stm->bindParam(":urlI", $urlImage, PDO::PARAM_STR);
        $stm->bindParam(":idMateria", $matiera, PDO::PARAM_INT);
        $stm->bindParam(":anno", $anno, PDO::PARAM_STR);

        $esito = $stm->execute();
        
        var_dump($stm->errorInfo());


    }

    public function ottieniAnni()
    {
        $sql = "SHOW COLUMNS FROM Riassunti WHERE Field = 'Anno'";
        $stm = $this->connessione->prepare($sql);
        $stm->execute();
        $esito = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
        $esito = $esito['Type'];  
        $esito = str_replace("enum('", "", $esito);
        $esito = str_replace("')", "", $esito);
        
        preg_match("/^enum\(\'(.*)\'\)$/", $esito);
        $enum = explode("','", $esito);
        return $enum;
    }

    public function getUtente($id)
    {
        $sql = "SELECT * from Utenti where Utenti.IDGoogle = :id";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $id, PDO::PARAM_STR);
        $stm->execute();
        $esito = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $esito;
    }

    public function creaUtente($username, $idGoogle, $ruolo, $mail)
    {
        $sql = "INSERT INTO `Utenti`(`IDGoogle`, `Mail`, `Username`, `Ruolo`) VALUES (:googleID, :mail, :un, :ruolo)";


        
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":googleID", $idGoogle, PDO::PARAM_STR);
        $stm->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stm->bindParam(":un", $username, PDO::PARAM_STR);
        $stm->bindParam(":ruolo", $ruolo, PDO::PARAM_STR);     

        $b = $stm->execute();
        $a = $stm->errorInfo();

        var_dump($a);

        return $b;
        
    }


}


