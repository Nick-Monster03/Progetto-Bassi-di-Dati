<?php

    if (isset($_POST["competenze"]) and isset($_POST["livello"])) {
        $competenza = $_POST["competenze"];
        $livello = $_POST["livello"];
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            exit();
        }
        $sql = "INSERT INTO PROFILO_SKILL (nomeProfilo, nomeProgettoSoftware, nomeSkill, livelloRichiesto) VALUES (:nomeProfilo, :nomeProgettoSoftware, :nomeSkill, :livelloRichiesto)";
        $res=$pdo->prepare($sql);
        $nome = $_COOKIE['nome'];
        $nomeProgettoSoftware = $_COOKIE['nomeProgettoSoftware'];
        $res->bindValue(":nomeProfilo", $nome);
        $res->bindValue(":nomeProgettoSoftware", $nomeProgettoSoftware);
        $res->bindValue(":nomeSkill",$competenza);
        $res->bindValue(":livelloRichiesto",$livello);
        $res->execute();
        header("Location: menageProfile.php?nome=" . urlencode($nome) . "&nomeProgettoSoftware=" . urlencode($nomeProgettoSoftware));
        exit();
    }
?>