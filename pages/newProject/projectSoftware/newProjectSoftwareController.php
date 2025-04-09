<?php
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($pdo)) {
        Init();
    }

    
    try {
        if (!isset($_POST['profileName']) || empty($_POST['profileName'])) {
            throw new Exception("Il nome del profilo non è definito.");
        }

        $projectName = $_COOKIE['nomeProgetto'];
        echo "<p>Nome Progetto: " . htmlspecialchars($projectName) . "</p>";
        $profileName = $_POST['profileName'];
        $sql = "CALL AggiungiProfilo (:profileName, :nomeProgettoSoftware)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':profileName', $profileName);
        $stmt->bindParam(':nomeProgettoSoftware', $projectName);
        $stmt->execute();
        header("Location: newProjectSoftware.php");
    } catch (PDOException $e) {
        echo "<p>[ERRORE] Errore durante la connsessione con il db: " . $e->getMessage() . $e->getLine() . "</p>";
        echo '<a href="../../home/home.php">Torna alla Home</a>';
    } 
    catch (Exception $e) {
        echo "<p>[ERRORE] " . $e->getMessage() . "</p>";
        echo '<a href="../../home/home.php">Torna alla Home</a>';
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
    $nomeProgettoSoftware = $_COOKIE['nomeProgetto'];

    $stmt = $pdo->prepare("SELECT * FROM PROFILO WHERE nomeProgettoSoftware = :nomeProgettoSoftware");

    $stmt->bindParam(":nomeProgettoSoftware", $nomeProgettoSoftware, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
   
?>