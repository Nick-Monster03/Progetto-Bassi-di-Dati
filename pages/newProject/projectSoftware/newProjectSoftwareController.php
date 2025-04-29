<?php
    global $pdo;
    include_once '../../../services/mostraErrore.php';

    $nomeProgettoSoftware = $_COOKIE['nomeProgetto'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($pdo)) {
            Init();
        }
            
        try {
            if (!isset($_POST['profileName']) || empty($_POST['profileName'])) {
                throw new Exception("Il nome del profilo non è definito.", 4404);
            }
            //echo "<p>Nome Progetto: " . htmlspecialchars($nomeProgettoSoftware) . "</p>";
            $profileName = $_POST['profileName'];
            $sql = "CALL AggiungiProfilo (:profileName, :nomeProgettoSoftware)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':profileName', $profileName);
            $stmt->bindParam(':nomeProgettoSoftware', $nomeProgettoSoftware);
            $stmt->execute();
            include "../../../services/log_eventi.php";
            addLog("nuovo_profilo", (object)['nomeProfilo' => $profileName, 'nomeProgettoSoftware' => $nomeProgettoSoftware]);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['creation_phase']) && $_SESSION['creation_phase'] === 0) {
                $_SESSION['creation_phase'] = 1;
            }
            header("Location: newProjectSoftware.php");
        } catch (PDOException $e) {
            $title =  "[ERRORE] : " . $e->getCode();
            //se il profilo esiste già allora dalla shcermata di errore torna alla precedente
            if($e->getCode() == 23000)
                mostraErrore($title, $e->getMessage(), '../projectSoftware/newProjectSoftware.php');
            else //altrimenti se c' è un errore più grave torna alla home
                mostraErrore($title, $e->getMessage(), '../../home/home.php');
            exit();
        } 
        catch (Exception $e) {
            $title =  "ERRORE: " . $e->getCode();
            mostraErrore($title, $e->getMessage(), '../../home/home.php');
            exit();
        }
        
    }

    function Init(){
        global $pdo;
        try {
            if(!isset ($_COOKIE['nomeProgetto'])) {
                throw new Exception("SESSIONE SCADUTA.", 444);
            }
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            session_start();
            $nomeProgettoSoftware = $_COOKIE['nomeProgetto'];

            $stmt = $pdo->prepare("SELECT * FROM PROFILO WHERE nomeProgettoSoftware = :nomeProgettoSoftware");

            $stmt->bindParam(":nomeProgettoSoftware", $nomeProgettoSoftware, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $title =  "[ERRORE] : " . $e->getCode();
            mostraErrore($title, $e->getMessage(), '../../home/home.php');
            exit();
        } 
        catch (Exception $e) {
            $title =  "ERRORE: " . $e->getCode();
            mostraErrore($title, $e->getMessage(), '../../home/home.php');
            exit();
        }
    }
   
?>