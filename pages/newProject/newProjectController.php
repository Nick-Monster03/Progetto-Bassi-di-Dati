<?php
    session_start();
    //questa variabile sarà creata solo ed unicamente nella fase in cui si crea un nuovo progetto
    //in maniera tale che un creatore non possa uscire prima di aver designato per un progetto
    //almeno un reward e almeno un profilo o componente
    $_SESSION['creation_phase'] = 0;
    include '../../services/mostraErrore.php';
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
        $img =file_get_contents($_FILES["immagine"]["tmp_name"]);


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
        
        //quando si torna alla home ogni coockie sarà cancellato
        setcookie("nomeProgetto", $nome, time() + 3600, "/");
        setcookie("creatore", $email_creatore, time() + 3600, "/");
        
        if($tipologia == "Hardware"){
            $sql='INSERT INTO progetto_hardware(nomeProgetto) VALUES("'.$nome.'")';
            $res=$pdo->exec($sql);
            header("Location: ../componenti/componenti.php");
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
        mostraErrore($e->getCode(), $e->getMessage(), "../home/home.php");
        if($pdo && $pdo->inTransaction()){
            $pdo->rollBack();
        }
    }catch(Exception $e){
        mostraErrore($e->getCode(), $e->getMessage(), "../home/home.php");
        echo("[ERRORE] " . $e->getMessage() .  "]");
        exit();
    }
?>