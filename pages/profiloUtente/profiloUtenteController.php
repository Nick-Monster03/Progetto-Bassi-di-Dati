<?php
    try{
        session_start();
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM UTENTE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $_SESSION["email"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SESSION['user_role'] == 'creatore') {
            $sql = "SELECT * FROM PROGETTO WHERE emailUtenteCreatore = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $_SESSION["email"]);
            $stmt->execute();
            $progettiCreati = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } 
        $sql = "SELECT nomeProgetto, SUM(importo) as totale FROM FINANZIAMENTO WHERE emailUtente = :email GROUP BY nomeProgetto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $_SESSION["email"]);
        $stmt->execute();
        $progettiFinanziati = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
    }catch(PDOException $e){
        echo "[ERRORE] Database non accessibile: " . $e->getMessage();
        exit();
    }
?>