<?php

    include '../../services/log_eventi.php';
    function getProjects() {
        try {
            session_start();
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $sql = 'SELECT nome FROM PROGETTO WHERE emailUtenteCreatore = "'.$_SESSION['email'].'" AND stato = "aperto" AND EXISTS (SELECT * FROM PROGETTO_SOFTWARE WHERE PROGETTO.nome = PROGETTO_SOFTWARE.nomeProgetto)';
            $res=$pdo->prepare($sql);
            $res->execute();
            return $res->fetchAll();
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            exit();
        }
    }

    function getCandidatures($nomeProgetto){
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
        $projects = getProjects();
        $candidaturesByProject = [];
        foreach ($projects as $project) {
            $nomeProgetto = $project['nome'];  
            $candidatures = getCandidatures($nomeProgetto);
            $candidaturesByProject[$nomeProgetto] = $candidatures;
        }
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
        addLog("nuovo_esitoCandidatura", (object)['nomeProfilo' => $nomeProfilo, 'nomeProgetto' => $nomeProgetto, 'esito' => $esito]);
        
 
        
        } catch (PDOException $e) {
            include '../../services/mostraErrore.php';
            echo "[ERRORE] Connessione al DB non riuscita. Errore: \n";
            mostraErrore($e->getCode(), $e->getMessage(), "../home/home.php");
            exit();
        } catch (Exception $e) {
            include '../../services/mostraErrore.php';
            echo "[ERRORE] : \n";
            mostraErrore($e->getCode(), $e->getMessage(), "../home/home.php");
            exit();
        }
    header("Location: candidatura.php");
    }
?>