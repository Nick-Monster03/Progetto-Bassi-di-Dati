<?php
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($pdo)) {
        Init();
    }
    $projectName = $_SESSION['projectName'];
    $profileName = $_POST['profileName'] ;

    if (!empty($profileName)) {
        try {
            $sql = "CALL AggiungiProfilo (:profileName, :nomeProgettoSoftware)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':profileName', $profileName);
            $stmt->bindParam(':nomeProgettoSoftware', $projectName);
            $stmt->execute();
            header("Location: newProjectSoftware.php");
        } catch (PDOException $e) {
            echo "[ERRORE] Errore durante l'inserimento dei dati: " . $e->getMessage();
        }
    } else {
        echo "[ERRORE] Tutti i campi sono obbligatori.";
    }
}

function Init(){
    global $pdo;
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    session_start();
    $nomeProgettoSoftware = $_SESSION['projectName'];

    $stmt = $pdo->prepare("SELECT * FROM PROFILO WHERE nomeProgettoSoftware = :nomeProgettoSoftware");

    $stmt->bindParam(":nomeProgettoSoftware", $nomeProgettoSoftware, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
   
?>