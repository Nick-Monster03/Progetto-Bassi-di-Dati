<?php
    include_once '../../services/mostraErrore.php';

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_GET['nome'])) {
            throw new Exception("Parametro 'nome' mancante.");
        }

        $nomeProgetto = $_GET['nome'];
        //il nome del progetto, il suo valoreAttuale(=soldi ricevuti finora) e il nome dell' utente creatore sono stati 
        //inseriti in cookie così da avere un tempo di limite di iterazione come si ha in molti siti web
        //per evitare un sovraccarico di operazioni
        setcookie("nomeProgetto", $nomeProgetto, time() + 3600, "/");
        $valoreAttuale = trovaImporto($nomeProgetto)["total"] ?? 0;
        setcookie("valoreAttuale", $valoreAttuale, time() + 3600, "/");
        //per debug echo($_COOKIE["nomeProgetto"]);
        $query = $pdo->prepare('SELECT * FROM Progetto WHERE nome = :nomeProgetto');
        $query->bindValue(':nomeProgetto', $nomeProgetto);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        setcookie("creatore", $result['emailUtenteCreatore'], time() + 3600, "/");
        $isSoftware = isSoftware($nomeProgetto);
        if ($result) {
            $query = $pdo->prepare('SELECT foto FROM FOTO WHERE nomeProgetto = :nomeProgetto LIMIT 1');
            $query->bindValue(':nomeProgetto', $nomeProgetto);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
           
            echo '<h2 class="text-center mb-4">Dettagli del Progetto: ' . htmlspecialchars($nomeProgetto, ENT_QUOTES, 'UTF-8') . '</h2>';

            if ($row && isset($row['foto'])) {
            $blob = $row['foto'];
            $base64Image = base64_encode($blob);
            echo "<img src='data:image/jpeg;base64," . htmlspecialchars($base64Image, ENT_QUOTES, 'UTF-8') . "' alt='Foto Progetto' style='max-width: 400px; margin: 10px;'>";
            } else {
                echo "<p>Nessuna foto disponibile.</p>";
            }
        } else {
            //per debug echo "<p>Nessun progetto trovato con il nome '$nomeProgetto'.</p>";
            throw new Exception("non è stato trovato nessun progetto con questo nome", 1017);
        }
        
    } catch (PDOException $e) {
        $title =  "[ERRORE] Database non accessibile: " . $e->getCode();
        mostraErrore($title, $e->getMessage(), '../home/home.php');
        exit();
    } catch (Exception $e) {
        $title = "[ERRORE] " . $e->getCode();
        mostraErrore($title, $e->getMessage(), '../home/home.php');
        exit();
    }
    

    function trovaImporto($progetto){
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $pdo->prepare("SELECT SUM(importo) as total FROM FINANZIAMENTO WHERE nomeProgetto = :nome");
            $res->bindValue("nome", $progetto);
            $res->execute();
            return $res->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            $title =  "[ERRORE] Database non accessibile: " . $e->getCode();
            mostraErrore($title, $e->getMessage(), '../home/home.php');
            exit();
        }
    }

    function isSoftware($nomeProgetto){
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $pdo->prepare("SELECT count(*) as count FROM PROGETTO_SOFTWARE WHERE nomeProgetto = :nomeProgetto");
            $res->bindValue(":nomeProgetto", $nomeProgetto);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_ASSOC);
            return $result['count'] >= 1;
        }catch(PDOException $e){
            $title =  "[ERRORE] Database non accessibile: " . $e->getCode();
            mostraErrore($title, $e->getMessage(), '../home/home.php');
            exit();
        }
    }
?>