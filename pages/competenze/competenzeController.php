<?php
    include '../../services/log_eventi.php';
    include '../../services/mostraErrore.php';
    session_start();

    try{
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $pdo->prepare("SELECT nome FROM skill");
        $query->execute();
        $competenze = $query->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_POST['nuovaCompetenza'])){
            $nuovaCompetenza = strtolower(trim($_POST['nuovaCompetenza']));
            $query = $pdo->prepare("CALL InserisciCompetenza(:nuovaCompetenza, :emailAmministratore)");
            $query->bindParam(':nuovaCompetenza', $nuovaCompetenza);
            $query->bindParam(':emailAmministratore', $_SESSION['email']);
            $query->execute();
            addLog("nuova_skill", (object)['nome'=>$nuovaCompetenza, 'amministratore'=>$_SESSION['email']]);
            header("Location: competenze.php");
            exit();
        }
    }catch(PDOException $e){
        mostraErrore($e->getCode(), $e->getMessage(), '../competenze/competenze.php');
        exit();
    }
?>