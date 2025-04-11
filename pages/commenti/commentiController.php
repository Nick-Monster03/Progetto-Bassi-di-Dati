<?php
    try {
        ini_set('display_errors', 1); // Mostra gli errori
ini_set('display_startup_errors', 1);
        include '../../services/log_eventi.php';
        if (!isset($_COOKIE["nomeProgetto"], $_COOKIE["creatore"]))
            throw new Exception("SESSIONE SCADUTA.");

        $nomeProgetto = $_COOKIE["nomeProgetto"];
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (isset($_POST['idCommento'], $_POST['emailUtenteCreatore'], $_POST['risposta'])){
            // Debug: Print POST values
            echo "<pre>";
            echo "idCommento: " . htmlspecialchars($_POST['idCommento']) . "\n";
            echo "emailUtenteCreatore: " . htmlspecialchars($_POST['emailUtenteCreatore']) . "\n";
            echo "risposta: " . htmlspecialchars($_POST['risposta']) . "\n";
            echo "</pre>";

            $idCommento = (int) $_POST['idCommento'];
            $emailCreatore = $_POST['emailUtenteCreatore'];
            $risposta = trim($_POST['risposta']);

            $stmt = $pdo->prepare("CALL RispondiCommento(:idCommento, :emailCreatore, :risposta)");
            $stmt->bindParam(':idCommento', $idCommento, PDO::PARAM_INT);
            $stmt->bindParam(':emailCreatore', $emailCreatore, PDO::PARAM_STR);
            $stmt->bindParam(':risposta', $risposta, PDO::PARAM_STR);
            $stmt->execute();
            addLog("nuova_risposta", new Risposta($idCommento, $emailCreatore, $risposta));
            
            header("Location: commenti.php");
            exit();
        }
        if(isset($_POST['testoCommento'])){
            session_start();
            $testo= $_POST['testoCommento'];
            $emailUtente = $_SESSION['email'];

            $sql = $pdo->prepare("CALL AggiungiCommento(:testo, :nomeProgetto, :emailUtente)");
            $sql->bindParam(':testo', $testo, PDO::PARAM_STR);
            $sql->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
            $sql->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
            $sql->execute();
            addLog("nuovo_commento", new Commento(null, $testo, null, $nomeProgetto, $emailUtente));//tanto sono necessari per il log solo nomeProgetto e emailUtente
            

            header("Location: commenti.php");
        }
        // Fetch comments
        $query = $pdo->prepare("SELECT * FROM commento WHERE nomeProgetto=:nomeProgetto");
        $query->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
        $query->execute();
        $commenti = $query->fetchAll(PDO::FETCH_ASSOC);

        $completeComments = [];

        foreach ($commenti as $commento) {
            $commentoObj = new Commento(
            $commento['id'],
            $commento['testo'],
            $commento['dataCommento'],
            $commento['nomeProgetto'],
            $commento['emailUtente']
            );

            // Fetch the single response for the current comment
            $query = $pdo->prepare("SELECT * FROM risposta WHERE idCommento=:idCommento LIMIT 1");
            $query->bindParam(':idCommento', $commento['id'], PDO::PARAM_INT);
            $query->execute();
            $risposta = $query->fetch(PDO::FETCH_ASSOC);

            $rispostaObj = null;
            if ($risposta) {
            $rispostaObj = new Risposta(
                $risposta['idCommento'],
                $risposta['emailUtenteCreatore'],
                $risposta['risposta']
            );
            }

            $completeComments[] = new CompleteComment($commentoObj, $rispostaObj);
        }

        
    } catch (PDOException $e) {
        echo "Errore di connessione al db: " . $e->getMessage();
        echo ' <div><a href="../home/home.php" class="btn btn-secondary">Home</a></div>';
        exit();
    } catch (Exception $e) {
        echo $e->getMessage();
        echo ' <div><a href="../home/home.php" class="btn btn-secondary">Home</a></div>';
        exit();
    }
 

    class Commento {
        private $id;
        private $testo;
        private $dataCommento;
        private $nomeProgetto;
        private $emailUtente;

        public function __construct($id, $testo, $dataCommento, $nomeProgetto, $emailUtente) {
            $this->id = $id;
            $this->testo = $testo;
            $this->dataCommento = $dataCommento;
            $this->nomeProgetto = $nomeProgetto;
            $this->emailUtente = $emailUtente;
        }

        public function getId() {
            return $this->id;
        }

        public function getTesto() {
            return $this->testo;
        }

        public function getDataCommento() {
            return $this->dataCommento;
        }

        public function getNomeProgetto() {
            return $this->nomeProgetto;
        }

        public function getEmailUtente() {
            return $this->emailUtente;
        }
    }

    class Risposta {
        private $idCommento;
        private $emailUtenteCreatore;
        private $risposta;

        public function __construct($idCommento, $emailUtenteCreatore, $risposta) {
            $this->idCommento = $idCommento;
            $this->emailUtenteCreatore = $emailUtenteCreatore;
            $this->risposta = $risposta;
        }

        public function getIdCommento() {
            return $this->idCommento;
        }

        public function getEmailUtenteCreatore() {
            return $this->emailUtenteCreatore;
        }

        public function getRisposta() {
            return $this->risposta;
        }
    }

    class CompleteComment{
        private $commento;
        private $risposte;

        public function __construct($commento, $risposte) {
            $this->commento = $commento;
            $this->risposte = $risposte;
        }

        public function getCommento() {
            return $this->commento;
        }

        public function getRisposte() {
            return $this->risposte;
        }
    }
?>