<?php
session_start();

try {
    include '../../../services/log_eventi.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_COOKIE['nomeProgetto']) || !isset($_FILES['foto'])) {
            throw new Exception("Sessione scaduta. Progetto non selezionato.");
        }

        $nomeProgetto = $_COOKIE['nomeProgetto'];
        $descrizione = $_POST['descrizione'];

        // if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['foto'];
            $uploadPath = __DIR__ . "/../../../services/uploads/" . basename($file["name"]);
            if (file_exists($uploadPath)) {
                echo "Errore: Il file esiste già nella cartella.";
                echo $file['tmp_name'];
            } else {
                if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
                    echo "File caricato correttamente.";
                } else {
                    echo "Errore nel caricamento.";
                }
            }
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("CALL InserisciReward(:descrizione, :foto, :nomeProgetto)");
            $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
            $stmt->bindParam(':foto', $file['name'], PDO::PARAM_STR);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);

            $stmt->execute();
            addLog('nuovo_reward', (object)['nomeProgetto' => $nomeProgetto]);
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