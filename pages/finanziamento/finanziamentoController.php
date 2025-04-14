<?php
    try{
        include '../../services/log_eventi.php';
        if(!isset($_COOKIE['nomeProgetto'])) {
            throw new Exception('SESSIONE SCADUTA. <a href="../home/home.php">backHome</a>');
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (isset($_SESSION['email'], $_COOKIE['nomeProgetto'], $_POST['importo'], $_POST['id_reward'])) {


                $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $emailUtente = $_SESSION['email'];
                $nomeProgetto = $_COOKIE['nomeProgetto'];
                $importo = $_POST['importo'];
                $reward_id = $_POST['id_reward']; 
               
                $sql = "CALL FinanziaProgetto(:emailUtente, :nomeProgetto, :importo, :reward_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
                $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
                $stmt->bindParam(':importo', $importo, PDO::PARAM_STR);
                $stmt->bindParam(':reward_id', $reward_id, PDO::PARAM_INT);
                $stmt->execute();
                addLog("nuovo_finanziamento", (object)['nomeProgetto'=>$nomeProgetto, 'utente'=>$emailUtente]);
                

                // Se l'esecuzione ha avuto successo, calcola la somma di tutti i finanziamenti per il progetto
                $stmt = $pdo->prepare("SELECT SUM(importo) AS totaleFinanziamenti FROM FINANZIAMENTO WHERE nomeProgetto = :nomeProgetto");
                $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Aggiorna il valore del cookie con il nuovo totale
                if ($result && isset($result['totaleFinanziamenti'])) {
                    setcookie('valoreAttuale', $result['totaleFinanziamenti'], time() + 3600, '/');
                }

                header('Location: ../finanziamento/finanziamento.php');
                exit();
            } else {
                throw new Exception('Dati mancanti.');
            }
        } else{
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            session_start();
            $nomeProgetto = $_COOKIE['nomeProgetto'];
            $emailUtente = $_SESSION['email'];
            $sql = "SELECT codice, descrizione, foto FROM REWARD WHERE nomeProgetto = :nomeProgetto "; //AND NOT EXISTS (SELECT * FROM FINANZIAMENTO WHERE REWARD.codice = FINANZIAMENTO.idReward)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
            $stmt->execute();
            $rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //verifico se l' utente ha effettuato un finanziamento per questo proeget nelle ultime 24 ore
            $oraLimite = new DateTime();                                      
            $oraLimite->modify('-1 day');
            $timestampLimite = $oraLimite->format('Y-m-d H:i:s');
            $stmt = $pdo->prepare("SELECT COUNT(*) AS numero FROM FINANZIAMENTO WHERE emailUtente = :email AND nomeProgetto = :progetto AND dataVersamento >= :limite");
            $stmt->bindParam(':email', $emailUtente);
            $stmt->bindParam(':progetto', $nomeProgetto);
            $stmt->bindParam(':limite', $timestampLimite);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['numero'] == 0 ) { //se non ha effettuato nessun finanziamento nelle ultime 24 ore allora può finazniare
                $flag_finanziamento = true;
                $motivazione = "";
            } else {
                $flag_finanziamento = false;
                $motivazione = $result['numero'] == 0 ? "nessun reward disponibile" : "hai già finanziato questo progetto nelle ultime 24 ore";
            }
        }
    }catch(PDOException $e){
            echo 'Errore di connessione: ' . $e->getMessage();
            echo "<a href='../item/item.php'>Torna alla pagina del progetto</a>";
            
            exit();
    }catch(Exception $e){
        echo 'Errore: ' . $e->getMessage();
    }

// //questa funzione mi serve per andare a prednere dal mio database l' id del primo reward disponibile, cioè che non è ancora stato assegnato
//     function getFirstReward($pdo, $nomeProgetto) {
//         $stmt = $pdo->prepare("SELECT codice FROM REWARD WHERE nomeProgetto = :nomeProgetto AND NOT EXISTS (SELECT 1 FROM FINANZIAMENTO WHERE REWARD.codice = FINANZIAMENTO.idReward) ORDER BY codice ASC LIMIT 1");
//         $stmt->bindParam(":nomeProgetto", $nomeProgetto);
//         $stmt->execute();

//         $result = $stmt->fetch(PDO::FETCH_ASSOC);
//         return $result['codice'] ?? null;
//     }
   
?>