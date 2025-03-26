<?php
    session_start();

    // Connessione al DB
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }

    $email = $_SESSION['email'];

    try {
        $query = $pdo->prepare("SELECT nomeskill, livello FROM CURRICULUM WHERE emailUtente = :email");
        $query->bindValue(":email", $email);
        $query->execute();

        $_SESSION['skills'] = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($skills)) {
            echo "[INFO] Nessuna skill trovata per questo amministratore.";
        }
    }
    catch (PDOException $e) {
        echo("[ERRORE] Query SQL (Insert) non riuscita. Errore: " . $e->getMessage());
        exit();
    }

    header("Location: ../skillList.php");
    exit();
?>