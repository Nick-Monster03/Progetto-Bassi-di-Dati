<?php
if (isset($_POST["email"]) and isset($_POST["role"])) {

    $email = $_POST["email"];
    $role = $_POST["role"];

    // Connessione al DB
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }

    try {
        $res = $pdo->prepare("SELECT * FROM UTENTE WHERE email = :email");
        $res->bindValue(":email",$email);
        $res->execute(); 
    } catch (PDOException $e) {
        echo("[ERRORE] Query SQL (Insert) non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    $row = $res->rowCount();
   
    if ($row> 0) {
        session_start();

        if($role == "utente" ){
            $resC = $pdo->prepare("SELECT * FROM CREATORE WHERE emailUtente = :email");
            $resC->bindValue(":email",$email);
            $resC->execute(); 
            $rowC = $resC->rowCount();
            $resA = $pdo->prepare("SELECT * FROM AMMINISTRATORE WHERE emailUtente = :email");
            $resA->bindValue(":email",$email);
            $resA->execute(); 
            $rowA = $resA->rowCount();
            if($rowC==0 && $rowA==0){ //verifica che non sia un creatore o un amministratore
                $_SESSION['authorized'] = 1;
                $_SESSION['email'] = $email;
                $_SESSION['user_role'] = $role;
                header("Location: home.php");
            }else{
                echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Non esiste un creatore con questa email</p>");
            }
        }else if($role == "creatore"){
            $resC = $pdo->prepare("SELECT * FROM CREATORE WHERE emailUtente = :email");
            $resC->bindValue(":email", $email);
            $resC->execute(); 
            $rowC = $resC->rowCount();
            if($rowC>0){
                $_SESSION['authorized'] = 1;
                $_SESSION['email'] = $email;
                $_SESSION['user_role'] = $role;
                header("Location: home.php");
            }else{
                //header("Location: login.php");
                echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Non esiste un creatore con questa email</p>");
            }
        }else if($role == "amministratore"){
            $_GLOBALS['check_security_code'] = 1;
            $_SESSION['email'] = $email;
        } else {
            echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Credenziali errate, riprova</p>");
        }
    } else {
        echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Credenziali errate, riprova</p>");
    }
}
if(isset($_POST["security_code"])){
    session_start();
    $email = $_SESSION['email'];
    $security_code = $_POST["security_code"];
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    try {
        $res = $pdo->prepare("SELECT * FROM amministratore WHERE emailUtente = :email AND codice_sicurezza = :security_code");
        $res->bindValue(":email", $email);
        $res->bindValue(":security_code", $security_code);
        $res->execute();
       
    } catch (PDOException $e) {
        echo("[ERRORE] Query SQL non riuscita. Errore: " . $e->getMessage());  
        exit();
    }
    $row = $res->rowCount();

    if($row > 0){
        $_SESSION['authorized'] = 1;
        $_SESSION['user_role'] = "amministratore";
        //echo("Login effettuato con successo. Email: " . $email . ", Codice di sicurezza: " . $security_code);
        header("Location: home.php");
    } else {
        echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Codice di sicurezza errato, riprova</p>");
        $_GLOBALS['check_security_code'] = 0;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <a href="home.php">&lt;Home</a>
    <form action='login.php' method="post">
        <br>
        <br>
        <hr> LOGIN </hr>
        <table>
            <tr>
                <td><b>Specifica che ruolo ricopri</b></td>
                <td>
                    <select name="role" id="role">
                        <option value="utente" selected>Utente</option>
                        <option value="creatore">Creatore</option>
                        <option value="amministratore">Amministratore</option>
                    </select>
                </td>
            </tr>
            <tr>
            <td><b>Inserisci la tua email:</b></td>
            <td><input type='text' name="email" id="email"></td>
            </tr>
        </table>
        <div><input type='submit' value='Login'></div>
    </form>
    <br>
    <?php
    session_start();
    if (isset($_GLOBALS['check_security_code']) && $_GLOBALS['check_security_code']== 1) {
    ?>
        <br>
        <form method="POST" action="login.php">
            <label for="security_code">Codice di Sicurezza:</label>
            <input type="password" id="security_code" name="security_code" required>
            <button type="submit">Invia</button>
        </form>
    <?php
    }
    ?>
</body>
</html>
