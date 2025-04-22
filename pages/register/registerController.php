<?php
// Controlla se il modulo è stato inviato
try{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        
        include_once '../../services/log_eventi.php';
        include_once '../../services/mostraErrore.php';
            if (!(isset($_POST['securityCode']) || (isset($_POST['userRole'], $_POST['email'], $_POST['nickname'], $_POST['password'], $_POST['nome'], $_POST['cognome'], $_POST['annoNascita'], $_POST['luogoNascita'])))) {
                throw new Exception("DATI MANCANTI");
            }

            $userRole = $_POST['userRole']; 
            $email = $_POST['email'];
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
            $annoNascita = $_POST['annoNascita']; // Riceve in formato YYYY-MM-DD
            $luogoNascita = $_POST['luogoNascita'];

            $sql = "CALL Registrazione(:email, :password, :nickname, :nome, :cognome, :annoNascita, :luogoNascita)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);
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
                    setcookie("user_password", $password, time() + 3600, "/");
                    setcookie("user_nickname", $nickname, time() + 3600, "/");
                    setcookie("user_nome", $nome, time() + 3600, "/");
                    setcookie("user_cognome", $cognome, time() + 3600, "/");
                    setcookie("user_annoNascita", $annoNascita, time() + 3600, "/");
                    setcookie("user_luogoNascita", $luogoNascita, time() + 3600, "/");

                    $query = "SELECT * FROM UTENTE WHERE email = :email";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
                    $stmt->execute();
                    $rows = $stmt->rowCount();

                    if ($rows == 0) {
                        $is_administrator = true;
                        $_SESSION['is_administrator'] = $is_administrator;
                        header("Location: register.php");
                    }
                    else {
                        throw new PDOException("Utente già registrato con questa email", 23000);
                    }
                    break;
                    
                default:
                    $message = "Seleziona un ruolo valido.";
                    break;
            }
        if (isset($_POST['securityCode'])) {
            
            $securityCode = $_POST['securityCode'];

            if (!isset($_COOKIE['user_email'], $_COOKIE['user_password'],$_COOKIE['user_nickname'], $_COOKIE['user_nome'], $_COOKIE['user_cognome'], $_COOKIE['user_annoNascita'], $_COOKIE['user_luogoNascita'])) {
                throw new Exception("SESSIONE SCADUTA");
            }

            $email = $_COOKIE['user_email'];
            $password = $_COOKIE['user_password'];
            $nickname = $_COOKIE['user_nickname'];
            $nome = $_COOKIE['user_nome'];
            $cognome = $_COOKIE['user_cognome'];
            $annoNascita = $_COOKIE['user_annoNascita'];
            $luogoNascita = $_COOKIE['user_luogoNascita'];

            $pdo->beginTransaction();
            $sql = "CALL Registrazione(:email, :password, :nickname, :nome, :cognome, :annoNascita, :luogoNascita)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);
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
            //prima di tornare alla  home distruggiamo tutti i cocckie che non sono più necessari
            setcookie("user_email", $email, time() - 3600, "/");
            setcookie("user_nickname", $nickname, time() - 3600, "/");
            setcookie("user_password", $password, time() - 3600, "/");
            setcookie("user_nome", $nome, time() - 3600, "/");
            setcookie("user_cognome", $cognome, time() - 3600, "/");
            setcookie("user_annoNascita", $annoNascita, time() - 3600, "/");
            setcookie("user_luogoNascita", $luogoNascita, time() - 3600, "/");
            unset($_SESSION['is_administrator']);

            header("Location: ../home/home.php");
            exit();
        }
    }
} catch (PDOException $e) {
    
    $title =  "[ERRORE] Di accesso al Database: " . $e->getCode();
    mostraErrore($title, $e->getMessage(), '../home/home.php');
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    setcookie("user_email", $email, time() - 3600, "/");
    setcookie("user_nickname", $nickname, time() - 3600, "/");
    setcookie("user_nome", $nome, time() - 3600, "/");
    setcookie("user_cognome", $cognome, time() - 3600, "/");
    setcookie("user_annoNascita", $annoNascita, time() - 3600, "/");
    setcookie("user_luogoNascita", $luogoNascita, time() - 3600, "/");
    setcookie("user_password", $password, time() - 3600, "/");
    unset($_SESSION['is_administrator']);

}catch (Exception $e) {
    $title =  "[ERRORE]: " . $e->getCode();
    mostraErrore($title, $e->getMessage(), '../home/home.php');
            
    if($pdo && $pdo->inTransaction()){
        $pdo->rollBack();
    }
    unset($_SESSION['is_administrator']);
}

?>