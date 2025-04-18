<?php
    try{
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $fotoProgetti =  [
        "progetto_ai.jpeg" => "Progetto AI",
        "ecommerce_platform.jpeg" => "E-commerce Platform",
        "cybersecurity_audit.jpeg" => "Cybersecurity Audit",
        "interfaccia_gestionale.jpeg" => "Interfaccia Gestionale",];
        $path = "./services/uploads/";
        $sql = "INSERT INTO FOTO (foto, nomeProgetto) VALUES (:foto, :nomeProgetto)";
        $stmt = $pdo->prepare($sql);
        foreach($fotoProgetti as $nomeFile => $nomeProgetto){
            $filePath = $path . $nomeFile;
            $uploadData=file_get_contents($filePath);
            $stmt->bindParam(':foto', $uploadData, PDO::PARAM_LOB);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto);
            $stmt->execute();
        }

        $rewardData = [
            ['descrizione' => 'Accesso anticipato alla beta', 'file' => 'reward_beta.jpg', 'progetto' => 'Progetto AI'],
            ['descrizione' => 'Certificato di partecipazione', 'file' => 'certificato.jpg', 'progetto' => 'E-commerce Platform'],
            ['descrizione' => '2% delle quote', 'file' => 'quote.jpg', 'progetto' => 'Progetto AI'],
        ];
    
        $path = "./services/uploads/";
        $sql = "CALL InserisciReward(:descrizione, :foto, :nomeProgetto)";
        $stmt = $pdo->prepare($sql);
        
        foreach ($rewardData as $reward) {
            $filePath = $path . $reward['file'];
            $uploadData = file_get_contents($filePath);
        
            $stmt->bindValue(':descrizione', $reward['descrizione']);
            $stmt->bindValue(':foto', $uploadData, PDO::PARAM_LOB);
            $stmt->bindValue(':nomeProgetto', $reward['progetto']);
        
            $stmt->execute();
        }
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage() .  "]");
    }
    try{
        require './services/vendor/autoload.php';
        $client = new MongoDB\Client("mongodb://localhost:27017");
        $db = $client->Movimenti;
        $collection = $db->log_eventi;
        $collection->deleteMany([]);

    }catch(Exception $e){
        echo("[ERRORE] connessione a mongo db non riuscita: " . $e->getMessage() .  "]");
        echo($e->getLine());
    }
?>