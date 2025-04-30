<?php
    try{
        include '../../services/log_eventi.php';
        include_once '../../services/mostraErrore.php';
        if(!isset($_COOKIE['nomeProgetto'])) {
            throw new Exception('SESSIONE SCADUTA. TORNA INDIETRO');
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (isset($_SESSION['email'], $_COOKIE['nomeProgetto'], $_POST['importo'], $_POST['id_reward'])) {


                $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $emailUtente = $_SESSION['email'];
                $nomeProgetto = $_COOKIE['nomeProgetto'];
              
                $reward_id = $_POST['id_reward']; 
                $importo = $_POST['importo']; // Conversione da stringa a intero

                $sql = "CALL FinanziaProgetto(:emailUtente, :nomeProgetto, :importo, :reward_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
                $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
                $stmt->bindParam(':importo', $importo, PDO::PARAM_INT);
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
            $timestampLimite = $oraLimite->format('Y-m-d'); //data attuale                                   
            //adesso preparo la query che controlli se l' utente ha effettuati un finanziamento per questo progetto nella data di oggi (da mezzanotte si intende)
            $stmt = $pdo->prepare("SELECT COUNT(*) AS numero FROM FINANZIAMENTO WHERE emailUtente = :email AND nomeProgetto = :progetto AND  dataVersamento >= :limite");
            $stmt->bindParam(':email', $emailUtente);
            $stmt->bindParam(':progetto', $nomeProgetto);
            $stmt->bindParam(':limite', $timestampLimite);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['numero'] == 0 && !empty($rewards)) { //se non trova nessun finanziamento effettuato nelle giornata di oggi e la lista di rewards non è vuota
                $flag_finanziamento = true;
                $motivazione = "";
            } else {
                $flag_finanziamento = false;
                $motivazione = $result['numero'] == 0 ? "nessun reward disponibile" : "hai già finanziato questo progetto nelle ultime 24 ore";
            }
        }
    }catch(PDOException $e){
        mostraErrore($e->getCode(), $e->getMessage(), '../home/home.php');
        exit();
    }catch(Exception $e){
        mostraErrore($e->getCode(), $e->getMessage(), '../home/home.php');
        exit();
    }


   
?>