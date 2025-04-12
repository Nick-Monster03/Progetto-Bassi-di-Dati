<?php
    try {
        if (isset($_POST["email"]) and isset($_POST["password"])) {

            $email = $_POST["email"];
            $password = $_POST["password"];
            
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $pdo->prepare("SELECT * FROM UTENTE WHERE email = :email AND password = :password");
            $res->bindValue(":email",$email);
            $res->bindValue(":password",$password);
            $res->execute(); 

            //per controllare che l' utente sia registrato omeno basta controllare se la nostra query restituisce almeno una riga
            //se non restituisce righe significa che l' utente non è registrato
            $row = $res->rowCount();
        
            if ($row> 0) {
                session_start();
                $_SESSION['email'] = $email;
                $resC = $pdo->prepare("SELECT * FROM CREATORE WHERE emailUtente = :email");
                $resC->bindValue(":email",$email);
                $resC->execute(); 
                $rowC = $resC->rowCount();

                $resA = $pdo->prepare("SELECT * FROM AMMINISTRATORE WHERE emailUtente = :email");
                $resA->bindValue(":email",$email);
                $resA->execute(); 
                $rowA = $resA->rowCount();

                if($rowC==0 && $rowA==0){ //verifica che non sia un creatore o un amministratore
                    $_SESSION['user_role'] = 'utente';
                    header("Location: ../home/home.php");
                }else if($rowC>0){
                    $_SESSION['user_role'] = 'creatore';
                    header("Location: ../home/home.php");
                }else if($rowA>0){
                    $_SESSION['user_role'] = 'amministratore';
                    header("Location: ./login.php");
                } 
            } else {
                echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Credenziali errate o utente non registrato, riprova</p>");
            }
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST["security_code"]) && (!isset($_POST["email"]) || !isset($_POST["password"]))) {
            throw new Exception("DATI MANCANTI");
            }
        }
           
        if(isset($_POST["security_code"])){
            session_start();
            $email = $_SESSION['email'];
            $security_code = $_POST["security_code"];
            
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $res = $pdo->prepare("SELECT * FROM amministratore WHERE emailUtente = :email AND codice_sicurezza = :security_code");
            $res->bindValue(":email", $email);
            $res->bindValue(":security_code", $security_code);
            $res->execute();
            
            $row = $res->rowCount();

            if($row > 0){
                header("Location: ../home/home.php");
            } else {
                echo("<p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Codice di sicurezza errato, riprova</p>");
                unset($_SESSION['user_role'], $_SESSION['email']);
            }
            exit();
        }

    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        echo("<br> <a href='../home/home.php'>Torna alla Home</a>");
        exit();
    }catch (Exception $e){
        echo ("[ERRORE] " . $e->getMessage());
        echo ("<br> <a href='./login.php'>Torna alla shcermata di Login</a>");
        exit();
    }
?>
