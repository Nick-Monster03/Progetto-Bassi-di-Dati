<?php
// Controlla se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userRole = $_POST['userRole']; 
    $email = $_POST['email'];
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    require '../../services/log_eventi.php';
    try {
        $nickname = $_POST['nickname'];
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $annoNascita = $_POST['annoNascita']; // Riceve in formato YYYY-MM-DD
        $luogoNascita = $_POST['luogoNascita'];


        $sql = "CALL Registrazione(:email, :nickname, :nome, :cognome, :annoNascita, :luogoNascita)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":nickname", $nickname, PDO::PARAM_STR);
        $stmt->bindValue(":nome", $nome, PDO::PARAM_STR);
        $stmt->bindValue(":cognome", $cognome, PDO::PARAM_STR);
        $stmt->bindValue(":annoNascita", $annoNascita, PDO::PARAM_STR); 
        $stmt->bindValue(":luogoNascita", $luogoNascita, PDO::PARAM_STR);
    
        switch ($userRole) {
            case 'utente':
                //echo "Registrazione avvenuta con successo!";
                $pdo->beginTransaction();
                $stmt->execute();
                $pdo->commit();
                addLog("nuovo_utente", (object) ["email" => $email, "ruolo" => $userRole]);
                header("Location: ../home/home.php");
                exit();
                break;

            case 'creatore':
                $pdo->beginTransaction();
                $stmt->execute();
                $sql = "CALL RegistrazioneCreatore(:email)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", $email, PDO::PARAM_STR);
                $stmt->execute();
                //echo "Registrazione avvenuta con successo come Creatore!";
                $pdo->commit();
                addLog("nuovo_utente", (object) ["email" => $email, "ruolo" => $userRole]);
                header("Location: ../home/home.php");
                exit();
                break;

            case 'amministratore':
                setcookie("user_email", $email, time() + 3600, "/");
                setcookie("user_nickname", $nickname, time() + 3600, "/");
                setcookie("user_nome", $nome, time() + 3600, "/");
                setcookie("user_cognome", $cognome, time() + 3600, "/");
                setcookie("user_annoNascita", $annoNascita, time() + 3600, "/");
                setcookie("user_luogoNascita", $luogoNascita, time() + 3600, "/");
                verifyAdmin();
                break;
                
            default:
                $message = "Seleziona un ruolo valido.";
                break;
        }
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "[ERRORE] Operazione non riuscita. Errore: " . $e->getMessage();
    }

    }
    function verifyAdmin() {
            echo '
            <form method="post">
                <h2>Codice di Sicurezza Richiesto</h2>
                <label for="securityCode" class="form-label">Inserisci il codice di sicurezza:</label>
                <input type="password" class="form-control" id="securityCode" name="securityCode" required>
                <br>
                <button type="submit" class="btn btn-danger">Verifica</button>
            </form>';
    }
    if (isset($_POST['securityCode'])) {
        try {
            $securityCode = $_POST['securityCode'];
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
                exit();
            }

            if (!isset($_COOKIE['user_email'], $_COOKIE['user_nickname'], $_COOKIE['user_nome'], $_COOKIE['user_cognome'], $_COOKIE['user_annoNascita'], $_COOKIE['user_luogoNascita'])) {
                throw new Exception("SESSIONE SCADUTA");
            }

            $email = $_COOKIE['user_email'];
            $nickname = $_COOKIE['user_nickname'];
            $nome = $_COOKIE['user_nome'];
            $cognome = $_COOKIE['user_cognome'];
            $annoNascita = $_COOKIE['user_annoNascita'];
            $luogoNascita = $_COOKIE['user_luogoNascita'];

            $pdo->beginTransaction();
            $sql = "CALL Registrazione(:email, :nickname, :nome, :cognome, :annoNascita, :luogoNascita)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":nickname", $nickname, PDO::PARAM_STR);
            $stmt->bindValue(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindValue(":cognome", $cognome, PDO::PARAM_STR);
            $stmt->bindValue(":annoNascita", $annoNascita, PDO::PARAM_STR); 
            $stmt->bindValue(":luogoNascita", $luogoNascita, PDO::PARAM_STR);
            $stmt->execute();
            $sql = "CALL RegistrazioneAmministratore(:email, :securityCode)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":securityCode", $securityCode, PDO::PARAM_STR);
            $stmt->execute();
            $pdo->commit();
            addLog("nuovo_utente", (object) ["email" => $email, "ruolo" => "amministratore"]);
            //echo "Registrazione avvenuta con successo come Amministratore!";
        } catch (PDOException $e) {
            echo "<script>alert('[ERRORE] Operazione non riuscita. Errore: " . $e->getMessage() . "');</script>";
            $pdo->rollBack();
        }
        catch (Exception $e) {
            echo($e->getMessage()) ;
            $pdo->rollBack();
        }
        header("Location: ../home/home.php");
        exit();
    }
  
    ?>