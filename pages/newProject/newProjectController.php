<?php
    session_start();
    $nome=$_POST['projectName'];
    $descrizione=$_POST['description'];
    $budget=$_POST['budget'];
    $data_limite=$_POST['endDate'];
    $tipologia=$_POST['tipologia'];
    $email_creatore=$_SESSION['email'];
    
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("CALL InserisciProgetto(:nome, :descrizione, :budget, :data_limite, :emailUtenteCreatore)");
        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
        $stmt->bindParam(":descrizione", $descrizione, PDO::PARAM_STR);
        $stmt->bindParam(":budget", $budget, PDO::PARAM_STR);
        $stmt->bindParam(":data_limite", $data_limite, PDO::PARAM_STR);
        $stmt->bindParam(":emailUtenteCreatore", $email_creatore, PDO::PARAM_STR);

        if($stmt->execute()){
            require '../../services/log_eventi.php';
            addLog("nuovo_progetto", (object) ["nome" => $nome, "descrizione" => $descrizione, "budget" => $budget, "data_limite" => $data_limite, "email_creatore" => $email_creatore]);
            $_SESSION['projectName'] = $nome;
        }


    
    
    if($tipologia == "Hardware"){
        $sql='INSERT INTO progetto_hardware(nomeProgetto) VALUES("'.$_SESSION['projectName'].'")';
        $res=$pdo->exec($sql);
        header("Location: projectHardware/newProjectHardware.php");
        exit();
    } else {
        $sql='INSERT INTO progetto_software(nomeProgetto) VALUES("'.$_SESSION['projectName'].'")';
        $res=$pdo->exec($sql);
        header("Location: projectSoftware/newProjectSoftware.php");
        exit();
    }
} catch (PDOException $e) {
    echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
    exit();
}

    
?>