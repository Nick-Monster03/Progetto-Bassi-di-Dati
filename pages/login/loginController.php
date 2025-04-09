<?php
if (isset($_POST["email"]) and isset($_POST["role"]) and isset($_POST["password"])) {

    $email = $_POST["email"];
    $role = $_POST["role"];
    $password = $_POST["password"];
    // Connessione al DB
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
        $res = $pdo->prepare("SELECT * FROM UTENTE WHERE email = :email AND password = :password");
        $res->bindValue(":email",$email);
        $res->bindValue(":password",$password);
        $res->execute(); 
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
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
                header("Location: ../home/home.php");
            }else{
                echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Non esiste un utente con questa email</p>");
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
                header("Location: ../home/home.php");
            }else{
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
    
        $res = $pdo->prepare("SELECT * FROM amministratore WHERE emailUtente = :email AND codice_sicurezza = :security_code");
        $res->bindValue(":email", $email);
        $res->bindValue(":security_code", $security_code);
        $res->execute();
       
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    $row = $res->rowCount();

    if($row > 0){
        $_SESSION['authorized'] = 1;
        $_SESSION['user_role'] = "amministratore";
        header("Location: ../home/home.php");
    } else {
        echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Codice di sicurezza errato, riprova</p>");
        $_GLOBALS['check_security_code'] = 0;
    }
}
?>
