<?php
    try{
        $pdo = new PDO("mysql:host=localhost;dbname=BOSTARTER", "root", "changeme");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        session_start();
        $emailUtente=$_SESSION['email'];
        $stmt = $pdo->prepare("SELECT nomeskill, livello FROM CURRICULUM WHERE emailutente =:emailutente");
        $stmt->bindValue(":emailutente", $emailUtente);
        $stmt->execute();
        $skill_utente = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['nomeProfilo'], $_POST['nomeProgettoSoftware'])) {
            throw new Exception("DATI MANCANTI");
        }
        else {
        $nomeProfilo = $_POST['nomeProfilo'];
        $nomeProgettoSoftware = $_POST['nomeProgettoSoftware'];
        
        // PER DEBUG
        // echo "<h3>DEBUG: Dati ricevuti dal form</h3>";
        // echo "<pre>";
        // echo "nomeProfilo: " . htmlspecialchars($nomeProfilo) . "\n";
        // echo "nomeProgettoSoftware: " . htmlspecialchars($nomeProgettoSoftware) . "\n";
        // echo "emailUtente: " . htmlspecialchars($emailUtente) . "\n";
        // echo "</pre>";
        $stmt = $pdo->prepare("CALL Candidati(:nomeProfilo, :nomeProgettoSoftware, :emailUtente)");
        $stmt->bindParam(":nomeProfilo", $nomeProfilo, PDO::PARAM_STR);
        $stmt->bindParam(":nomeProgettoSoftware", $nomeProgettoSoftware, PDO::PARAM_STR);
        $stmt->bindParam(":emailUtente", $emailUtente, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: confermaCandidatura.php"); 
        exit();
        }
    }catch(PDOException $e){
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }catch(Exception $e){
        echo("SESSIONE SCADUTA");
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