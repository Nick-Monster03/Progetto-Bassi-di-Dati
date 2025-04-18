<?php
    include '../../services/log_eventi.php';

    session_start();

    try{
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $pdo->prepare("SELECT nome FROM skill");
        $query->execute();
        $competenze = $query->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_POST['nuovaCompetenza'])){
            $nuovaCompetenza = $_POST['nuovaCompetenza'];
            $query = $pdo->prepare("INSERT INTO skill (nome, emailAmministratore) VALUES (:nome, :emailAmministratore)");
            $query->bindParam(':emailAmministratore', $_SESSION['email']);
            $query->bindParam(':nome', $nuovaCompetenza);
            $query->execute();
            addLog("nuova_skill", (object)['nome'=>$nuovaCompetenza]);
            
            header("Location: competenze.php");
            exit();
        }
    }catch(PDOException $e){
        echo "Errore: " . $e->getMessage();
        echo "<a href='../home/home.php'>Torna alla Home</a>";
        exit();
    }
?>