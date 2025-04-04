<?php
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_COOKIE['nomeProgetto'])) {
            throw new Exception("Sessione scaduta. Progetto non selezionato.");
        }

        $nomeProgetto = $_COOKIE['nomeProgetto'];
        $descrizione = $_POST['descrizione'];

        // if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['foto']['name'];

            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO REWARD (descrizione, foto, nomeProgetto) VALUES (:descrizione, :foto, :nomeProgetto)");
            $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
            $stmt->bindParam(':foto', $fileName, PDO::PARAM_STR);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);

            $stmt->execute();
            header("Location: newReward.php");
            exit();
        // } else {
        //     echo "Errore nel caricamento del file.";
        // }
    } else {
        echo "Richiesta non valida.";
    }
} catch (PDOException $e) {
    echo "Errore del database: " . $e->getMessage();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>