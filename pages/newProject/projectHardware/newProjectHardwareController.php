<?php

    function getList(){
        try {
            
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $sql = 'SELECT nome FROM COMPONENTE';
            $res=$pdo->prepare($sql);
            $res->execute();
            return $res->fetchAll();
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            exit();
        }
       
    }
    function getComponentsIn(){
        try {
                session_start(); // Assicuriamoci che la sessione sia attiva
                if (!isset($_COOKIE['nomeProgetto'])) {
                    throw new Exception("[ERRORE] Nome del progetto non definito nella sessione.");
                }
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $sql = 'SELECT nomeComponente, quantita FROM COMPONENTI_PROGETTO WHERE nomeProgettoHardware ="' . $_COOKIE['nomeProgetto'] . '"';
            $res=$pdo->prepare($sql);
            $res->execute();
            return $res->fetchAll();
        } catch (PDOException $e) {
            error_log("[ERRORE] Connessione al DB fallita: " . $e->getMessage());
            return []; ;
        }
    }

    if(isset ($_POST['hardware']) && isset($_POST['quantity'])){
        session_start();
        try {
            $nomeProgetto = $_COOKIE['nomeProgetto'] ;
            $nomeComponente = $_POST['hardware'];
            $quantità = $_POST['quantity'];

            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'INSERT INTO Componenti_Progetto(nomeProgettoHardware, nomeComponente, quantita) VALUES("'.$nomeProgetto.'","'.$nomeComponente.'","'.$quantità.'")';
            $res=$pdo->prepare($sql);
            $res->execute();
            header('Location: ./newProjectHardware.php');
        }catch(PDOException $e){
            echo("[ERRORE] INSERT non riuscita " . $e->getMessage());
            echo '<a href="./newProjectHardware.php">Torna alla Home</a>';
            exit();
        }
    }
?>