<?php
include_once '../../services/mostraErrore.php';
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recupero del valore del cookie
        if (isset($_COOKIE['nomeProgetto']) || isset($_COOKIE['creatore'])) {
            $nomeProgetto = $_COOKIE['nomeProgetto'];

            // Query per ottenere i reward dalla tabella REWARD, che però non sono ancora stati assegnati a nessun finanziamento
            $sql = "SELECT codice, descrizione,foto FROM REWARD WHERE nomeProgetto = :nomeProgetto"; //AND NOT EXISTS (SELECT * FROM FINANZIAMENTO WHERE REWARD.codice = FINANZIAMENTO.idReward)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
            $stmt->execute();
            $rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } else {
            throw new Exception("SESSIONE SCADUTA");
        }
    } catch (PDOException $e) {
        $title =  "[ERRORE] Database non accessibile: " . $e->getCode();
        mostraErrore($title, $e->getMessage(), '../home/home.php');
        exit();
    }catch (Exception $e) {
        $title =  "[ERRORE]: " . $e->getCode();
        mostraErrore($title, $e->getMessage(), '../home/home.php');
        exit();
    }
?>