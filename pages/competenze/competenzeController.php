<?php
    try{
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $pdo->prepare("SELECT * FROM skill");
        $query->execute();
        $competenze = $query->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_POST['nuovaCompetenza'])){
            $nuovaCompetenza = $_POST['nuovaCompetenza'];
            $query = $pdo->prepare("INSERT INTO skill (nome) VALUES (:nome)");
            $query->bindParam(':nome', $nuovaCompetenza);
            $query->execute();
            header("Location: competenze.php");
            exit();
        }
    }catch(PDOException $e){
        echo "Errore: " . $e->getMessage();
        echo "<a href='../home/home.php'>Torna alla Home</a>";
        exit();
    }
?>