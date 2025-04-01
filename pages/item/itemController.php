<?php    
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_GET['nome'])) {
            throw new Exception("Parametro 'nome' mancante.");
        }

        $nomeProgetto = $_GET['nome'];
        //il nome del progetto è stato inserito in cookie così da avere un tempo di limite di iterazione
        setcookie("nomeProgetto", $nomeProgetto, time() + 3600, "/");
        //per debug echo($_COOKIE["nomeProgetto"]);
        $query = $pdo->prepare('SELECT * FROM Progetto WHERE nome = :nomeProgetto');
        $query->bindValue(':nomeProgetto', $nomeProgetto);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "<h2>Dettagli del Progetto:</h2>";
            foreach ($result as $row) {
                foreach ($row as $column => $value) {
                    echo "<p><strong>$column:</strong> $value</p>";
                    if($column == "emailUtenteCreatore")
                        $creatore = $value;
                    else if($column == "stato" )
                        $stato = $value;
                }
            }
            $query = $pdo->prepare('SELECT * FROM FOTO WHERE nomeProgetto = :nomeProgetto');
            $query->bindValue(':nomeProgetto', $nomeProgetto);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            $foto = $result["foto"];
            echo "<img src=.$foto. alt='fotoProgetto'></img>";
            $valoreAttuale = trovaImporto($nomeProgetto)["total"] ?? 0;
        } else {
            //per debug echo "<p>Nessun progetto trovato con il nome '$nomeProgetto'.</p>";
            throw new Exception("non è stato trovato nessun progetto con qeusto nome");
        }
        
    } catch (PDOException $e) {
        echo "[ERRORE] Database non accessibile: " . $e->getMessage();
        exit();
    } catch (Exception $e) {
        header("Location: home.php");
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
            echo "[ERRORE] Database non accessibile: " . $e->getMessage();
            exit();
        }
    }
?>