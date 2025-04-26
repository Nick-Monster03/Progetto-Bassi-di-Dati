<?php
   include_once '../../services/mostraErrore.php';
    if (isset($_POST["competenze"]) and isset($_POST["livello"])) {
        
        $competenza = $_POST["competenze"];
        $livello = $_POST["livello"];
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sqlCheck = "SELECT * FROM PROFILO_SKILL WHERE nomeProfilo = :nomeProfilo AND nomeProgettoSoftware = :nomeProgettoSoftware AND nomeSkill = :nomeSkill";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $nome = $_COOKIE['nomeProfilo'];
            $nomeProgettoSoftware = $_COOKIE['nomeProgettoSoftware'];
            $stmtCheck->bindValue(":nomeProfilo", $nome);
            $stmtCheck->bindValue(":nomeProgettoSoftware", $nomeProgettoSoftware);
            $stmtCheck->bindValue(":nomeSkill", $competenza);
            $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            //echo 'sei entrato';
            echo "<div>
                <p>La competenza esiste già. Vuoi sovrascriverla?</p>
                <form method='post' action='menageProfileController.php'>
                    <input  name='competenze' id='competenze' value='" . htmlspecialchars($competenza) . "'>
                    <input  name='newlivello' id='newlivello' value='" . htmlspecialchars($livello) . "'>
                    <input  name='nome' value='" . htmlspecialchars($nome) . "'>
                    <input  name='nomeProgettoSoftware' value='" . htmlspecialchars($nomeProgettoSoftware) . "'>
                    <button type='submit' name='overwrite' id='overwrite' value=1>Sovrascrivi</button>
                    <button type='submit' name='cancel' id='cancel' value=1>Annulla</button>
                </form>
            </div>";
            exit();
        } else {
            // No conflict, proceed with insert
            $sql = "INSERT INTO PROFILO_SKILL (nomeProfilo, nomeProgettoSoftware, nomeSkill, livelloRichiesto) VALUES (:nomeProfilo, :nomeProgettoSoftware, :nomeSkill, :livelloRichiesto)";
            $res = $pdo->prepare($sql);
            $res->bindValue(":nomeProfilo", $nome);
            $res->bindValue(":nomeProgettoSoftware", $nomeProgettoSoftware);
            $res->bindValue(":nomeSkill", $competenza);
            $res->bindValue(":livelloRichiesto", $livello);
            $res->execute();
            
        }

        header("Location: menageProfile.php?nome=" . urlencode($nome) . "&nomeProgettoSoftware=" . urlencode($nomeProgettoSoftware));
        exit();
        } catch (PDOException $e) {
            $title = "Errore di connessione al database ". $e->getCode();
            mostraErrore($title, $e->getMessage(), '../home/home.php');
            exit();
        }
    }

    //QUESTO BLOCCO DI CODICE SERVE PER GESTIRE IL CASO IN CUI IO VOGLIA SOVRASCIVERE UNA SKILL GIA' DEFINITA
    if (isset($_POST['overwrite'])) {
        $competenza = $_POST["competenze"];
        $livello = $_POST["newlivello"];
        $nome = $_POST["nome"];
        $nomeProgettoSoftware = $_POST["nomeProgettoSoftware"];

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE PROFILO_SKILL SET livelloRichiesto = :livelloRichiesto WHERE nomeProfilo = :nomeProfilo AND nomeProgettoSoftware = :nomeProgettoSoftware AND nomeSkill = :nomeSkill";
            $res = $pdo->prepare($sql);
            $res->bindValue(":nomeProfilo", $nome);
            $res->bindValue(":nomeProgettoSoftware", $nomeProgettoSoftware);
            $res->bindValue(":nomeSkill", $competenza);
            $res->bindValue(":livelloRichiesto", $livello);
            $res->execute();

            header("Location: menageProfile.php?nome=" . urlencode($nome) . "&nomeProgettoSoftware=" . urlencode($nomeProgettoSoftware));
            exit();
        } catch (PDOException $e) {
            $title = "Errore di connessione al database ". $e->getCode();
            mostraErrore($title, $e->getMessage(), '../home/home.php');
            exit();
        }
    }
    if (isset($_POST['cancel'])) {
        $nome = $_POST["nome"];
        $nomeProgettoSoftware = $_POST["nomeProgettoSoftware"];
        header("Location: menageProfile.php?nome=" . urlencode($nome) . "&nomeProgettoSoftware=" . urlencode($nomeProgettoSoftware));
        exit();
    }
    function existed($nomeProfilo, $nomeProgettoSoftware){
        //piccola funzioni che controlli che il profilo sia stato creato correttamente
        //per quel progetto software
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sqlCheck = "SELECT * FROM PROFILO WHERE nome = :nomeProfilo AND nomeProgettoSoftware = :nomeProgettoSoftware";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(":nomeProfilo", $nomeProfilo);
            $stmtCheck->bindValue(":nomeProgettoSoftware", $nomeProgettoSoftware);
            $stmtCheck->execute();
            return $stmtCheck->rowCount() > 0;

        }catch (PDOException $e) {
            $title = "Errore di connessione al database ". $e->getCode();
            mostraErrore($title, $e->getMessage(), '../home/home.php');
            exit();
        }
    }
?>