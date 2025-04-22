<?php
    include '../../services/log_eventi.php';
    include '../../services/mostraErrore.php';
    try{
        if(!isset($_COOKIE['nomeProgetto']))
            throw new Exception("SESSIONE SCADUTA");
        $nomeProgetto = $_COOKIE['nomeProgetto'];
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // $sql = "SELECT cp.nomeComponente, c.prezzo, cp.quantita FROM COMPONENTI_PROGETTO cp JOIN COMPONENTE c ON cp.nomeComponente = c.nome WHERE cp.nomeProgettoHardware=:nomeProgetto";
        // $stmt = $pdo->prepare($sql);
        // $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
        // $stmt->execute();

        $sql = "SELECT * FROM COMPONENTE WHERE nomeProgettoHardware = :nomeProgetto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
        $stmt->execute();

        $componenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nomeComponente'], $_POST['quantita'], $_POST['prezzo'], $_POST['descrizione'])) {
            $nomeComponente = trim($_POST['nomeComponente']);
            $quantita = $_POST['quantita'];
            $prezzo = $_POST['prezzo'];
            $descrizione = $_POST['descrizione'];
            $sql = "INSERT INTO COMPONENTE (nome, descrizione, prezzo, quantita, nomeProgettoHardware) VALUES (:nomeComponente, :descrizione, :prezzo, :quantita, :nomeProgetto)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
            $stmt->bindParam(':nomeComponente', $nomeComponente, PDO::PARAM_STR);
            $stmt->bindParam(':quantita', $quantita, PDO::PARAM_INT);
            $stmt->bindParam(':prezzo', $prezzo, PDO::PARAM_STR);
            $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
            $stmt->execute();
            addLog("nuovo_componente", (object)['nomeComponente'=>$nomeComponente, 'nomeProgetto'=>$nomeProgetto]);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if(isset($_SESSION['creation_phase']) && $_SESSION['creation_phase'] == 0)
                $_SESSION['creation_phase'] = 1;
            header("Location: ./componenti.php");
        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST') { // Se non sono stati forniti i dati necessari
            echo "Errore: non sono stati forniti i dati necessari per aggiungere un componente.";
            echo '<a href="../home/home.php">Torna alla home</a>';
            exit();
        }

    }catch(PDOEXCEPTION $e){
        echo "Errore: di connessone al database" ;
        mostraErrore($e->getCode(), $e->getMessage(), '../home/home.php');
        exit();
    }catch(Exception $e){
        echo  "ERRORE";
        mostraErrore($e->getCode(), $e->getMessage(), '../home/home.php');
        exit();
    }
    // finally {
    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(E_ALL);
    // }

    // function getComponentiInutilizzati($nomeProgetto) {
    // try{
    //     if(!isset($_COOKIE['nomeProgetto']))
    //         throw new Exception("SESSIONE SCADUTA");
    //     $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $sql = "SELECT nome FROM COMPONENTE c WHERE NOT EXISTS (SELECT * FROM COMPONENTI_PROGETTO WHERE nomeProgettoHardware = :nomeProgetto AND nomeComponente = c.nome)";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }catch(PDOEXCEPTION $e){
    //     echo "Errore: di connessone al database" . $e->getMessage();
    //     echo '<a href="../home/home.php">Torna alla home</a>';
    //     exit();
    // }catch(Exception $e){
    //     echo  $e->getMessage();
    //     echo '<a href="../home/home.php">Torna alla home</a>';
    //     exit();
    // }        
    // }
?>