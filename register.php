<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Registrazione</title>
</head>
<body>
    
    <form action="register.php" method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
        </div>
        <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>
            <input type="text" class="form-control" id="nickname" name="nickname">
        </div>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" class="form-control" id="cognome" name="cognome">
        </div>
        <div class="mb-3">
            <label for="annoNascita" class="form-label">Anno di Nascita</label>
            <input type="date" class="form-control" id="annoNascita" name="annoNascita">
        </div>
        <div class="mb-3">
            <label for="luogoNascita" class="form-label">Luogo di Nascita</label>
            <input type="text" class="form-control" id="luogoNascita" name="luogoNascita">
        </div>
        <div class="mb-3 ">
            <label for="userRole" class="form-label">Seleziona il tuo ruolo</label>
            <select class="form-select" id="userRole" name="userRole">
                <option value="utente">Utente</option>
                <option value="creatore">Creatore</option>
                <option value="amministratore">Amministratore</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
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
    require 'log_eventi.php';
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
        $stmt->execute();
        setcookie("user_email", $email, time() + 3600, "/"); 
        setcookie("user_role", $userRole, time() + 3600, "/");// Salva l'email e il ruolo in un cookie per 1 ora
        switch ($userRole) {
            case 'utente':
                echo "Registrazione avvenuta con successo!";
                addLog("nuovo_utente", (object) ["email" => $email, "ruolo" => $userRole]);
                setcookie("user_email", $email, time() - 3600, "/"); 
                setcookie("user_role", $userRole, time() - 3600, "/");
                header("Location: home.php");
                exit();
                break;

            case 'creatore':
                $sql = "INSERT INTO CREATORE (emailUtente) VALUES (:email)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", $email, PDO::PARAM_STR);
                $stmt->execute();
                echo "Registrazione avvenuta con successo come Creatore!";
                addLog("nuovo_utente", (object) ["email" => $email, "ruolo" => $userRole]);
                setcookie("user_email", $email, time() - 3600, "/"); 
                setcookie("user_role", $userRole, time() - 3600, "/");
                header("Location: home.php");
                exit();
                break;

            case 'amministratore':
                verifyAdmin();
                break;
                
            default:
                $message = "Seleziona un ruolo valido.";
                break;
        }
        
    } catch (PDOException $e) {
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
            $email = $_COOKIE['user_email'];
            $securityCode = $_POST['securityCode'];
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
                exit();
            }
            $sql = "INSERT INTO AMMINISTRATORE(emailUtente, codice_sicurezza) VALUES (:email, :securityCode)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":securityCode", $securityCode, PDO::PARAM_STR);
            $stmt->execute();
            $userRole = $_COOKIE['user_role'];
            addLog("nuovo_utente", (object) ["email" => $email, "ruolo" => $_COOKIE['user_role']]);
            echo "Registrazione avvenuta con successo come Amministratore!";
            setcookie("user_email", $email, time() - 3600, "/"); 
            setcookie("user_role", $userRole, time() - 3600, "/");
        } catch (PDOException $e) {
            echo "<script>alert('[ERRORE] Operazione non riuscita. Errore: " . $e->getMessage() . "');</script>";
        }
        header("Location: home.php");
        exit();
    }
  
    ?>
</body>
</html>