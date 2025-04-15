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
        

            
            $file = $_FILES['foto'];
            $uploadPath =  $file["tmp_name"];
            $blobFile = file_get_contents($uploadPath);
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("CALL InserisciReward(:descrizione, :foto, :nomeProgetto)");
            $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
            $stmt->bindParam(':foto', $blobFile, PDO::PARAM_STR);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);

            $stmt->execute();
            addLog('nuovo_reward', (object)['nomeProgetto' => $nomeProgetto]);
            $_SESSION['creation_phase'] = 2;
            header("Location: newReward.php");
            exit();
        
    } else {
        echo "Richiesta non valida.";
    }
} catch (PDOException $e) {
    echo "Errore del database: " . $e->getMessage();
    echo '<a href="./newReward.php">Torna alla Home</a>';
    exit();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
    echo '<a href="./newReward.php">Torna alla Home</a>';
    exit();
}
?>