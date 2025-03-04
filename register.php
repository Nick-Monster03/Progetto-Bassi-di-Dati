<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<body>
    <h1>Modulo di Registrazione</h1>
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
    $userRole = $_POST['userRole']; // Recupera il valore selezionato
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    $email = $_POST['email'];
    setcookie("user_email", $email, time() + 3600, "/"); // Salva l'email in un cookie per 1 ora
    
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
            $stmt->bindValue(":annoNascita", $annoNascita, PDO::PARAM_STR); // Formato già corretto
            $stmt->bindValue(":luogoNascita", $luogoNascita, PDO::PARAM_STR);
            $stmt->execute();
    echo "<script>console.log('userRole: " . $email . "');</script>";
    switch ($userRole) {
        case 'utente':
            echo "Registrazione avvenuta con successo!";
            break;

        case 'creatore':
            $sql = "INSERT INTO CREATORE (emailUtente) VALUES (:email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            echo "Registrazione avvenuta con successo come Creatore!";
            break;

        case 'amministratore':
            verifyAdmin();
            exit();
            break;
        default:
        $message = "Seleziona un ruolo valido.";
        break;
    }
}
if (isset($_POST['securityCode'])) {
    $email = $_COOKIE['user_email'];
    $securityCode = $_POST['securityCode'];
    echo "Codice di Sicurezza: $securityCode<br>";
    echo "Email: $email<br>";
    $message = "Accesso Admin confermato! Benvenuto Amministratore.";
    $sql = "INSERT INTO AMMINISTRATORE(emailUtente, codice_sicurezza) VALUES (:email, :securityCode)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":securityCode", $securityCode, PDO::PARAM_STR);
            $stmt->execute();
            echo "Registrazione avvenuta con successo come Amministratore!";
            unset($_COOKIE['user_email']); // Elimina il cookie
    
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
    echo "<h2>$message</h2>";

?>
</body>
</html>