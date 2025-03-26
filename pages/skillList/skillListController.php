<?php
    session_start();

    // Connessione al DB
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }

    if(isset($_POST["competenzeDisponibili"]) && isset($_POST["level"])) {
        $competenza = $_POST['competenzeDisponibili'];
        $livello = $_POST['level'];
        $emailUtente = $_SESSION["email"];
        echo($livello);
       

        try{
            $query = $pdo->prepare("INSERT INTO CURRICULUM(nomeskill, emailUtente, livello) VALUES (:nomeskill, :emailUtente, :livello)");
            $query->bindParam(':nomeskill', $competenza, PDO::PARAM_STR);
            $query->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
            $query->bindParam(':livello', $livello, PDO::PARAM_INT);
            $query->execute();

            header("Location: ../skillList/skillList.php");
        }
        catch(PDOException $e){
            echo("[ERRORE] Query SQL (Insert) non riuscita. Errore: " . $e->getMessage());
            exit();
        }
        
    }

    // header("Location: ../skillList.php");
    //exit();

    function getSkills() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            exit();
        }

        $query = $pdo->prepare('SELECT * FROM SKILL');
        $query->execute();
        return $query->fetchAll();
    }

    function getSkillsUser($emailUtente) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $query = $pdo->prepare("SELECT nomeskill, livello FROM CURRICULUM WHERE emailUtente = :email");
            $query->bindValue(":email", $emailUtente);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo("[ERRORE] Query SQL (Insert) non riuscita. Errore: " . $e->getMessage());
            exit();
        }
    }
?>