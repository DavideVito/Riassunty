<?php
error_reporting(0);
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

	public function mostraRiassunto($id = NULL)
    {
        
    	$sql = "SELECT * FROM Riassunti WHERE 1 ";
        if($id !== NULL)
        {
            $sql .= " and IDRiassunto = :id ";
        }
        
        $stm = $this->connessione->prepare($sql);
        if($id !== NULL)
        {
            $stm->bindParam(":id", $id, PDO::PARAM_INT);
        }

        $esito = $stm->execute();
        
        if($esito === false)
        {
            var_dump($stm->errorInfo());
        }
        
        return $stm->fetchAll(PDO::FETCH_ASSOC);   
    }

    public function rimuoviRiassunto($id)
    {
        echo $id;
        $sql = "SELECT * FROM Riassunti WHERE Riassunti.IDRiassunto = :id";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $id, PDO::PARAM_INT);

        $stm->execute();

        //var_dump($stm->errorInfo());
        
        $ris = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
        
        $posizionePDF = "../".$ris['UrlPDF'];
        $posizioneIMG = "../".$ris['UrlIMG'];
        
        echo json_encode($ris);
        unlink($posizionePDF);
        unlink($posizioneIMG);

        $sql = "DELETE FROM `Riassunti` WHERE `IDRiassunto` = :id";
       
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $id, PDO::PARAM_INT);
        $stm->execute();
        
        var_dump($stm->errorInfo());
       
        
        
        
        
    }
    

    public function getRiassunto($idMateria, $anno, $proprietario, $tipo, $nome)
    {

        $sql = "SELECT * FROM `v_RiassuntiApprovati` as `Riass` WHERE 1 "; 
        
        if(($tipo === "Master" || $tipo === "Docente"))
        {
            $idMateria = NULL;
            $anno = NULL;
            $proprietario = NULL;
        }
        
        if($idMateria !== NULL)
        {
            $sql .= " and `Riass`.IDMateria = :id ";
        }
       
        if($anno !== NULL)
        {
            $sql .= "and `Riass`.Anno = :anno ";
        }

        if($nome !== NULL)
        {
            $sql .=" and `Riass`.Titolo like :titolo";
        }

        if(!($tipo === "Master" || $tipo === "Docente"))
        {

        }

        if($proprietario !== NULL)
        {
            $sql .= " and `Riass`.IDUtente = :utente ";
        }
    
        $stm = $this->connessione->prepare($sql);
        if($idMateria !== NULL)
        {
            $stm->bindParam(":id", $idMateria, PDO::PARAM_INT);
        }

        if($anno !== NULL)
        {
            $stm->bindParam(":anno", $anno, PDO::PARAM_STR);
        }

        if($proprietario !== NULL)
        {
            $stm->bindParam(":utente", $proprietario, PDO::PARAM_INT);
        }

        if($nome !== NULL)
        {
            $nome = "%".$nome."%";
            $stm->bindParam(":titolo", $nome, PDO::PARAM_STR);
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

    public function inserisci($nomePdf, $fsPDF, $sha, $immagine, $indirizzo, $matiera, $anno, $idUtente)
    {
        $sql = "INSERT INTO `Riassunti`(`Titolo`, `IDUtente`, `UrlPDF`, `UrlIMG`, `IDMateria`, `Anno`) VALUES (:titolo, :IDUtente ,:urlP, :urlI, :idMateria, :anno); ";
       
        //:titolo, :urlP, :urlI, :idMateria, :anno, null
        $baseUrlRiass = "Riassunti/";
        $baseUrlImage = 'Immagini/';

        $urlImage = $baseUrlImage . $immagine . $sha .".png";
        //$urlPdf = $baseUrlRiass . $fsPDF . $sha . ".html";
        $urlPdf = $baseUrlRiass . $fsPDF . $sha;
        $stm = $this->connessione->prepare($sql);

        

        $stm->bindParam(":titolo", $nomePdf, PDO::PARAM_STR);
        $stm->bindParam(":IDUtente", $idUtente, PDO::PARAM_INT);
        $stm->bindParam(":urlP", $urlPdf, PDO::PARAM_STR);
        $stm->bindParam(":urlI", $urlImage, PDO::PARAM_STR);
        $stm->bindParam(":idMateria", $matiera, PDO::PARAM_INT);
        $stm->bindParam(":anno", $anno, PDO::PARAM_STR);

        $esito = $stm->execute();
        if($esito)
        {
            return $esito;
        }
        return $stm->errorCode();
        


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

    public function getUtente($id, $tipo = "google")
    {
        if($tipo === "google")
        {
            $sql = "SELECT * from Utenti where Utenti.IDGoogle = :id";
        }
        else
        {
            $sql = "SELECT * from Utenti where Utenti.IDUtente = :id";
        }
        
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
        if($b === false)
        {
            $a = $stm->errorInfo();

            var_dump($a);
        }
        

        $idCreato = $this->connessione->lastInsertId();
        return $this->getUtente($idCreato, "normale");
        
    }

    public function getRiassuntiNonApprovati($idMateria, $anno, $proprietario, $tipo = "Studente")
    {
        $sql = "SELECT * FROM `v_RiassuntiNonAprrovati` as `Riass` WHERE 1";
        $stm = $this->connessione->prepare($sql);
        if(!($tipo === "Master" || $tipo === "Docente"))
        {
            if($proprietario === null)
            {
                $arr['shouldRedirect'] = "true";
                $arr['motivo'] = "Non sei un account abilitato a tale funzione, se pensi ci sia un errore, contattaci";
                echo json_encode($arr);
            }
            die();
        }
        if($idMateria !== NULL)
        {
            $sql .= " and IDMateria = :id ";
        }
       
        if($anno !== NULL)
        {
            $sql .= "and Anno = :anno ";
        }
        
        if($proprietario !== NULL)
        {
            $sql .= " and IDUtente = :utente ";
        }
       
        $stm = $this->connessione->prepare($sql);
        if($idMateria !== NULL)
        {
            $stm->bindParam(":id", $idMateria, PDO::PARAM_INT);
        }

        if($anno !== NULL)
        {
            $stm->bindParam(":anno", $anno, PDO::PARAM_STR);
        }

        if($proprietario !== NULL)
        {
            $stm->bindParam(":utente", $proprietario, PDO::PARAM_INT);
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

    function approvaRiassunto($idRiassunto, $approvatoDa)
    {
        $sql = "INSERT INTO `RiassuntiApprovati`(`IDRiassunto`, `ApprovatoDa`) VALUES (:id,:da)";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $idRiassunto, PDO::PARAM_INT);
        $stm->bindParam(":da", $approvatoDa, PDO::PARAM_INT);
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }
       
        return $stm->fetchAll(PDO::FETCH_ASSOC);   
    }

    public static function generateToken()
    {
        return hash("sha512" ,bin2hex(random_bytes(1000000)));
    }
    public static function generaCodiceRiassunto()
    {
        return hash("sha1" , Connessione::generateToken());
    }

    public function inserisciToken($idUtente, $token)
    {
        $sql = "INSERT INTO `Tokens`(`Token`, `IDUtente`, `Scadenza`) VALUES (:token, :idUtente, :scadenza);";

        $scadenza =  $token["expires_at"] / 1000;

        $datetimeFormat = 'Y-m-d H:i:s';
        $date = new \DateTime();
        $date->setTimestamp($scadenza + 7200);
        $scadenza = $date->format($datetimeFormat);

        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":token", $token["id_token"], PDO::PARAM_STR);
        $stm->bindParam(":idUtente", $idUtente, PDO::PARAM_INT);
        $stm->bindParam(":scadenza", $scadenza, PDO::PARAM_STR);
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }

        return $scadenza;
    }

    private function eliminaRecordVecchi()
    {
        $sql = "DELETE FROM `Tokens` WHERE Scadenza < CURRENT_TIMESTAMP";
        $stm = $this->connessione->prepare($sql);
        $esito = $stm->execute();
    }


    public function controllaValidita($token, $obbligo = true)
    {
        //$this->eliminaRecordVecchi();
        $sql = "SELECT Token as a, IDUtente as b FROM Tokens WHERE `Tokens`.`Scadenza` > CURRENT_TIMESTAMP and `Tokens`.`Token`= :token";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":token", $token, PDO::PARAM_STR);
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }

        $esito = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
        if($obbligo)
        {
            if($esito === null)
            {
                $arr['shouldRedirect'] = "true";
                echo json_encode($arr);
                die();
            }
        }
        return $esito;
    }

    public function inserisciRiassutoTemporaneo($idRiassunto, $idUtente, $nome)
    {
        $sql = "INSERT INTO `RiassuntiTemporanei`(`IDRiassunto`, `IDUtente`, `Nome`) VALUES (:id,:idUtente,:nome)";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $idRiassunto, PDO::PARAM_STR);
        $stm->bindParam(":idUtente", $idUtente, PDO::PARAM_INT);
        $stm->bindParam(":nome", $nome, PDO::PARAM_STR);

        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
            die();
        }
    }

    public function creaNuovaVersione($idFile, $idRiassunto, $posizione)
    {
        $sql = "INSERT INTO `VersioniRiassuntiTemporanei`(`IDFile`, `IDRiassunto`, `Posizione`) VALUES (:idFile,:idRiassunto,:posizione)";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":idFile", $idFile, PDO::PARAM_STR);
        $stm->bindParam(":idRiassunto", $idRiassunto, PDO::PARAM_STR);
        $stm->bindParam(":posizione", $posizione, PDO::PARAM_STR);
        
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
            die();
        }

    }

    public function getRiassuntiTemporanei($di)
    {
        $sql = "select * from RiassuntiTemporanei where IDUtente = :id";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":id", $di, PDO::PARAM_INT);
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }

        $riassuntiTemporanei =  $stm->fetchAll(PDO::FETCH_ASSOC);

        $daRet = [];

        foreach($riassuntiTemporanei as $riassunto)
        {
            $sql = "SELECT * FROM v_RiassuntiTemporanei where IDRiassunto = :idRiassunto";
            $stm = $this->connessione->prepare($sql);
            $stm->bindParam(":idRiassunto", $riassunto['IDRiassunto'], PDO::PARAM_STR);
            $esito = $stm->execute();
            $versioni = $stm->fetchAll(PDO::FETCH_ASSOC);  
            
            $t = $riassunto;
            $t['versioni'] = $versioni;

            array_push($daRet, $t);

        }

        return $daRet;
    }

    public function getPosizioneFileTemporanero($idRiassunto, $idFile)
    {
        $sql = "SELECT Posizione, Nome FROM `v_RiassuntiTemporanei` where IDFile = :idFile and IDRiassunto = :idRiassunto";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":idFile", $idFile, PDO::PARAM_STR);
        $stm->bindParam(":idRiassunto", $idRiassunto, PDO::PARAM_STR);
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }

        return $stm->fetchAll(PDO::FETCH_ASSOC)[0];  
    }

    public function modificaValutazione($idUtente, $valutazione, $idRiassunto)
    {
        $sql = "UPDATE `Valutazioni` SET `Valutazione` = :valutazione WHERE `Valutazioni`.`IDRiassunto` = :idRiassunto AND `Valutazioni`.`IDUtente` = :idUtente;";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":idRiassunto", $idRiassunto, PDO::PARAM_INT);
        $stm->bindParam(":valutazione", $valutazione, PDO::PARAM_STR);
        $stm->bindParam(":idUtente", $idUtente , PDO::PARAM_STR);
        $esito = $stm->execute();
        if($esito === false)
        { 
            $a = $stm->errorInfo();
            /*echo $sql;
            echo "<br>";
             var_dump($a);*/
        }
	
        return $esito;
    }

    public function inserisciValutazione($idUtente, $valutazione, $idRiassunto)
    {
        $sql = "INSERT INTO `Valutazioni`(`IDRiassunto`, `Valutazione`, `IDUtente`) VALUES (:idRiassunto,:valutazione,:idUtente)";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":idRiassunto", $idRiassunto, PDO::PARAM_INT);
        $stm->bindParam(":valutazione", $valutazione, PDO::PARAM_INT);
        $stm->bindParam(":idUtente", $idUtente, PDO::PARAM_STR);
        $esito = $stm->execute();
        if($esito === false)
        { 
            $a = $stm->errorInfo();
            /*echo $sql;
            echo "<br>";
            var_dump($a);*/
        }
        return $esito;


    }

    public function mostraValutazione($idRiassunto)
    {
        $sql = "SELECT * FROM `v_Valutazioni` where 1 and IDRiassunto = :idRiassunto";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam(":idRiassunto", $idRiassunto, PDO::PARAM_INT);
        $esito = $stm->execute();
        if($esito === false)
        { $a = $stm->errorInfo();
            echo $sql;
            echo "<br>";
            var_dump($a);
        }
        $a = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $a[0];  
    }

}


