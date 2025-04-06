<?php
    try {
        
        if (!isset($_COOKIE["nomeProgetto"]))
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
            header("Location: commenti.php");
            exit();
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

            // Fetch responses for the current comment
            $query = $pdo->prepare("SELECT * FROM risposta WHERE idCommento=:idCommento");
            $query->bindParam(':idCommento', $commento['id'], PDO::PARAM_INT);
            $query->execute();
            $risposte = $query->fetchAll(PDO::FETCH_ASSOC);

            $risposteObj = null;
            if (!empty($risposte)) {
                $risposteObj = [];
                foreach ($risposte as $risposta) {
                    $risposteObj[] = new Risposta(
                        $risposta['idCommento'],
                        $risposta['emailUtenteCreatore'],
                        $risposta['risposta']
                    );
                }
            }

            $completeComments[] = new CompleteComment($commentoObj, $risposteObj);
        }

        
    } catch (PDOException $e) {
        echo "Errore di connessione al db: " . $e->getMessage();
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