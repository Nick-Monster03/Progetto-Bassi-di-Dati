<?php
    session_start();
    try {

        if (!isset($_POST['projectName'], $_POST['description'], $_POST['budget'], $_POST['endDate'], $_POST['tipologia'], $_FILES['immagine'])) {
            throw new Exception("DATI MANCANTI");
        }
        $nome = $_POST['projectName'];
        $descrizione = $_POST['description'];
        $budget = $_POST['budget'];
        $data_limite = $_POST['endDate'];
        $tipologia = $_POST['tipologia'];
        $email_creatore = $_SESSION['email'];
        $img = basename($_FILES["immagine"]["name"]);


        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("CALL InserisciProgetto(:nome, :descrizione, :budget, :data_limite, :emailUtenteCreatore, :fotoProgetto)");
        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
        $stmt->bindParam(":descrizione", $descrizione, PDO::PARAM_STR);
        $stmt->bindParam(":budget", $budget, PDO::PARAM_STR);
        $stmt->bindParam(":data_limite", $data_limite, PDO::PARAM_STR);
        $stmt->bindParam(":emailUtenteCreatore", $email_creatore, PDO::PARAM_STR);
        $stmt->bindParam(":fotoProgetto", $img, PDO::PARAM_STR);

        $pdo->beginTransaction();
        $stmt->execute();

        $file = $_FILES["immagine"];
        $uploadPath = __DIR__ . "/../../services/uploads/" . basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $uploadPath);
        

        setcookie("nomeProgetto", $nome, time() + 3600, "/");
        
        if($tipologia == "Hardware"){
            $sql='INSERT INTO progetto_hardware(nomeProgetto) VALUES("'.$nome.'")';
            $res=$pdo->exec($sql);
            header("Location: projectHardware/newProjectHardware.php");
        } else {
            $sql='INSERT INTO progetto_software(nomeProgetto) VALUES("'.$nome.'")';
            $res=$pdo->exec($sql);
            header("Location: projectSoftware/newProjectSoftware.php");
        }
        $pdo->commit();
        require '../../services/log_eventi.php';
        addLog("nuovo_progetto", (object) ["nome" => $nome, "descrizione" => $descrizione, "budget" => $budget, "data_limite" => $data_limite, "email_creatore" => $email_creatore]);
        exit();
        
    
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage() .  "]");
        echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
        if($pdo && $pdo->inTransaction()){
            $pdo->rollBack();
        }
    }catch(Exception $e){
        echo("[ERRORE] " . $e->getMessage() .  "]");
        echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
        exit();
    }
?>