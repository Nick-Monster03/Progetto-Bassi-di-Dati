<?php
    try{
        session_start();
        include '../../services/log_eventi.php';
        $pdo = new PDO("mysql:host=localhost;dbname=BOSTARTER", "root", "changeme");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // --- Se è POST, gestisci la candidatura
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['nomeProfilo'], $_POST['nomeProgettoSoftware'], $_SESSION['email'])) {
                throw new Exception("SESSIONE SCADUTA o DATI MANCANTI");
            }
        
            $stmt = $pdo->prepare("CALL Candidati(:nomeProfilo, :nomeProgettoSoftware, :emailUtente)");
            $stmt->bindParam(':nomeProfilo', $_POST['nomeProfilo'], PDO::PARAM_STR);
            $stmt->bindParam(':nomeProgettoSoftware', $_POST['nomeProgettoSoftware'], PDO::PARAM_STR);
            $stmt->bindParam(':emailUtente', $_SESSION['email'], PDO::PARAM_STR);
            $stmt->execute();
            addLog("nuova_candidatura", (object)['emailUtente' => $_SESSION['email'],'nomeProgettoSoftware' => $_POST['nomeProgettoSoftware'], 'nomeProfilo' => $_POST['nomeProfilo']]);
            header("Location: ../richiestaCandidatura/richiestaCandidatura.php");
            exit();
            
        }
        

        if (!isset($_COOKIE['nomeProgetto'])) {
            throw new Exception("SESSIONE SCADUTA");
        }
        
        $nomeProgetto = $_COOKIE['nomeProgetto'];
        
        $stmt = $pdo->prepare("SELECT nomeProfilo, nomeSkill, livelloRichiesto
            FROM PROFILO_SKILL
            WHERE nomeProgettoSoftware = :progetto
            ORDER BY nomeProfilo");
        $stmt->bindValue(":progetto", $nomeProgetto);
        $stmt->execute();
        $profili = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Skill dell’utente
        $emailUtente = $_SESSION['email'];
        $stmt = $pdo->prepare("SELECT nomeskill, livello FROM CURRICULUM WHERE emailutente = :emailutente");
        $stmt->bindValue(":emailutente", $emailUtente);
        $stmt->execute();
        $skill_utente = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT esito FROM CANDIDATURA WHERE nomeProfilo = :nomeProfilo AND emailUtente = :emailUtente AND nomeProgettoSoftware = :nomeProgetto");
        $stmt->bindParam(':nomeProfilo', $nomeProfilo, PDO::PARAM_STR);
        $stmt->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
        $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
        $stmt->execute();

        $esito = $stmt->fetchColumn();

    }catch(PDOException $e){
        echo("[ERRORE] Connessione al DB non riuscita. Errore " . $e->getMessage() );
        echo '<a href="../home/home.php">Torna alla Home</a>';
        exit();
    }catch(Exception $e){
        echo "[ERRORE] " . $e->getMessage() . "<br>";
        echo '<a href="../home/home.php">Torna alla Home</a>';
    }

    function checkCandidatura($nomeProfilo, $emailUtente, $nomeProgetto){
        try{
            $pdo = new PDO("mysql:host=localhost;dbname=BOSTARTER", "root", "changeme");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT esito FROM CANDIDATURA WHERE nomeProfilo = :nomeProfilo AND emailUtente = :emailUtente AND nomeProgettoSoftware = :nomeProgetto");
            $stmt->bindParam(':nomeProfilo', $nomeProfilo, PDO::PARAM_STR);
            $stmt->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
            $stmt->execute();
            $esito = $stmt->fetchColumn();
            return $esito;
        } catch(PDOException $e){
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            echo '<a href="../home/home.php">Torna alla Home</a>';
            exit();
        }
       
    }

    
    

    class Competenza{
        public $nomeSkill; 
        public $livello;
        public function __construct($s,$l)
        {
            $this->nomeSkill=$s;
            $this->livello=$l;
        }
        public function getLivello(){
            return $this->livello;
        }
        public function getSkill(){
            return $this->nomeSkill;
        }
        public function equalOrUpper(Competenza $c){
            if($c->getSkill() == $this->nomeSkill && $c->getLivello() >= $this->livello)
                return true;
            else 
                return false;
        }
    }
?>