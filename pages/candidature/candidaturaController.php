<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    function getProjects() {
        try {
            session_start();
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $sql = 'SELECT nome FROM PROGETTO WHERE emailUtenteCreatore = "'.$_SESSION['email'].'"';
            $res=$pdo->prepare($sql);
            $res->execute();
            return $res->fetchAll();
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            exit();
        }
    }

    function getCandatures($nomeProgetto){
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $sql = 'SELECT * FROM CANDIDATURA WHERE nomeProgettoSoftware = "'.$nomeProgetto.'"';
            $res=$pdo->prepare($sql);
            $res->execute();
            return $res->fetchAll();
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            exit();
        }
    }

    function getCandidaturesByProject() {
        // Ottieni tutti i progetti per l'utente
        $projects = getProjects();
        
        // Array per memorizzare le candidature per ogni progetto
        $candidaturesByProject = [];
    
        // Cicla attraverso tutti i progetti
        foreach ($projects as $project) {
            $nomeProgetto = $project['nome'];  // Prendi il nome del progetto
            
            // Ottieni tutte le candidature per il progetto corrente
            $candidatures = getCandatures($nomeProgetto);
    
            // Aggiungi le candidature per questo progetto nell'array associativo
            $candidaturesByProject[$nomeProgetto] = $candidatures;
        }
    
        // Restituisci l'array associativo con le candidature per progetto
        return $candidaturesByProject;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

        $emailUtente = $_POST['emailUtente'];
        $nomeProgetto = $_POST['nomeProgetto'];
        $nomeProfilo = $_POST['nomeProfilo'];
        $esito = $_POST['esito'];

        $sql = "CALL AccettaRichiesta(:nomeCandidato, :nomeProgetto, :profilo, :accettazione)";
        $stmt = $pdo->prepare($sql);

        // Binding dei parametri
        $stmt->bindParam(':nomeCandidato',  $emailUtente);
        $stmt->bindParam(':nomeProgetto', $nomeProgetto);
        $stmt->bindParam(':profilo', $nomeProfilo);
        $newEsito = $esito == 'accettata' ? 1 : 0;
        $stmt->bindParam(':accettazione', $newEsito);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage();
        exit();
    }
    header("Location: candidatura.php");
    }
?>