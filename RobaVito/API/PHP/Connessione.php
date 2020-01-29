<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Connessione {

    private $connessione = null;

    public function disconnetti() {
        $this->connessione = null;
    }

    public function __construct() {
        try {
            $host = "127.0.0.1";
            $dataBase = "my_riassunty";
            $uid = "riassunty";
            $pwd = "";
            $this->connessione = new PDO("mysql:host=$host;dbname=$dataBase", $uid, $pwd);
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            die(); // Lo script termina
        }
    }

    public function getScuole() {
        $sql = "SELECT * FROM Scuola";
        $stm = $this->connessione->prepare($sql);

        $esito = $stm->execute();
        if ($esito == 1) {
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return null;
    }

    public function getIndirizzi($id) {
        
        $sql = "select * from Indirizzo where IDScuola = :id";

        $stm = $this->connessione->prepare($sql);

        $stm->bindParam(":id", $id, PDO::PARAM_STR);

        $esito = $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnni($idIndirizzo) {
        $sql = "select * from AnnoScolastico where IDIndirizzo = :id";

        $stm = $this->connessione->prepare($sql);

        $stm->bindParam(":id", $idIndirizzo, PDO::PARAM_STR);

        $stm->execute();
        $ris = $stm->fetchAll(PDO::FETCH_ASSOC);
        
        return $ris;
    }

    public function getMaterie($idAnno) {
        $sql = "SELECT * from Materia where Materia.IDAnno = :id";

        $stm = $this->connessione->prepare($sql);

        $stm->bindParam(":id", $idAnno, PDO::PARAM_STR);

        $stm->execute();
        return  $stm->fetchAll(PDO::FETCH_ASSOC);
    }
        
        

    function getRiassunti($id) {
        $sql = "SELECT * from File where IDMateria = :id";

        $stm = $this->connessione->prepare($sql);

        $stm->bindParam(":id", $id, PDO::PARAM_INT);
        
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    function getRiassunto($id) {
        $sql = "SELECT * from File where IDFile = :id";

        $stm = $this->connessione->prepare($sql);

        $stm->bindParam(":id", $id, PDO::PARAM_INT);
        
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC)[0];
    }
}
