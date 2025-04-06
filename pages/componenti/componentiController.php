<?php
    try{
        if(!isset($_COOKIE['nomeProgetto']))
            throw new Exception("SESSIONE SCADUTA");
        $nomeProgetto = $_COOKIE['nomeProgetto'];
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT cp.nomeComponente, c.prezzo, cp.quantita FROM COMPONENTI_PROGETTO cp JOIN COMPONENTE c ON cp.nomeComponente = c.nome WHERE cp.nomeProgettoHardware=:nomeProgetto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
        $stmt->execute();

        $componenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_POST['nomeComponente'], $_POST['quantita'])){
            $nomeComponente = $_POST['nomeComponente'];
            $quantita = $_POST['quantita'];
            $sql = "INSERT INTO COMPONENTI_PROGETTO (nomeProgettoHardware, nomeComponente, quantita) VALUES (:nomeProgetto, :nomeComponente, :quantita)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
            $stmt->bindParam(':nomeComponente', $nomeComponente, PDO::PARAM_STR);
            $stmt->bindParam(':quantita', $quantita, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: ./componenti.php");
        }

    }catch(PDOEXCEPTION $e){
        echo "Errore: di connessone al database" . $e->getMessage();
        echo '<a href="../home/home.php">Torna alla home</a>';
        exit();
    }catch(Exception $e){
        echo  $e->getMessage();
        echo '<a href="../home/home.php">Torna alla home</a>';
        exit();
    }

    function getComponentiInutilizzati($nomeProgetto) {
    try{
        if(!isset($_COOKIE['nomeProgetto']))
            throw new Exception("SESSIONE SCADUTA");
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT nome FROM COMPONENTE c WHERE NOT EXISTS (SELECT * FROM COMPONENTI_PROGETTO WHERE nomeProgettoHardware = :nomeProgetto AND nomeComponente = c.nome)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nomeProgetto', $nomeProgetto, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOEXCEPTION $e){
        echo "Errore: di connessone al database" . $e->getMessage();
        echo '<a href="../home/home.php">Torna alla home</a>';
        exit();
    }catch(Exception $e){
        echo  $e->getMessage();
        echo '<a href="../home/home.php">Torna alla home</a>';
        exit();
    }        

    }
?>