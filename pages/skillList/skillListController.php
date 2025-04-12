 <?php
    // session_start();

    // try{
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["competenzeDisponibili"]) && isset($_POST["level"])) {
    //         $competenza = $_POST['competenzeDisponibili'];
    //         $livello = $_POST['level'];
    //         $emailUtente = $_SESSION["email"];
    //     }
    //     elseif($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST["competenzeDisponibili"], $_POST["level"])) {
    //         throw new Exception("SESSIONE SCADUTA o DATI MANCANTI");
    //     }
    //     $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $query = $pdo->prepare("INSERT INTO CURRICULUM(nomeskill, emailUtente, livello) VALUES (:nomeskill, :emailUtente, :livello)");
    //     $query->bindParam(':nomeskill', $competenza, PDO::PARAM_STR);
    //     $query->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
    //     $query->bindParam(':livello', $livello, PDO::PARAM_INT);
    //     $query->execute();
    //     include '../../services/log_eventi.php';
    //     addLog("nuova_Competenza", (object)['nuovaCompetenza'=>$competenza, 'emailUtente'=>$emailUtente]);
    //     header("Location: ../skillList/skillList.php");
    // }
    // catch(PDOException $e){
    //     echo("[ERRORE] Problemi di connessione con il database. Errore: " . $e->getMessage());
    //     echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
    //     exit();
    // }catch (Exception $e) {
    //     echo "[ERRORE] " . $e->getMessage();
    //     echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
    //     exit();
    // }


    // // header("Location: ../skillList.php");
    // //exit();

    // function getSkills() {
    //     try {
    //         $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
    //             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    //         ]);
    //         $query = $pdo->prepare('SELECT * FROM SKILL');
    //         $query->execute();
    //         return $query->fetchAll();
    //     } catch (PDOException $e) {
    //         echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
    //         echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
    //         exit();
    //     }
    // }

    // function getSkillsUser($emailUtente) {
    //     try {
    //         $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
    //             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    //         ]);
    //         $query = $pdo->prepare("SELECT nomeskill, livello FROM CURRICULUM WHERE emailUtente = :email");
    //         $query->bindValue(":email", $emailUtente);
    //         $query->execute();

    //         return $query->fetchAll(PDO::FETCH_ASSOC);
    //     }
    //     catch (PDOException $e) {
    //         echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
    //         echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
    //         exit();
    //     }
    // }
    session_start();

   
    try{
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["competenzeDisponibili"]) && isset($_POST["level"])) {
            $competenza = $_POST['competenzeDisponibili'];
            $livello = $_POST['level'];
            $emailUtente = $_SESSION["email"];
            
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $pdo->prepare("INSERT INTO CURRICULUM(nomeskill, emailUtente, livello) VALUES (:nomeskill, :emailUtente, :livello)");
            $query->bindParam(':nomeskill', $competenza, PDO::PARAM_STR);
            $query->bindParam(':emailUtente', $emailUtente, PDO::PARAM_STR);
            $query->bindParam(':livello', $livello, PDO::PARAM_INT);
            $query->execute();
            include '../../services/log_eventi.php';
            addLog("nuova_competenza", (object)['nuovaCompetenza'=>$competenza, 'emailUtente'=>$emailUtente]);
            header("Location: ../skillList/skillList.php");

        }else if($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST["competenzeDisponibili"]) || !isset($_POST["level"])))
            throw new Exception("DATI MANCANTI");

    }catch (PDOException $e) {
        echo("[ERRORE] Problemi di connessione con il database. Errore: " . $e->getMessage());
        echo '<a href="./skillList.php" class="btn btn-secondary">Torna alla Home</a>';
        exit();
    }catch (Exception $e) {
        echo "[ERRORE] " . $e->getMessage();
        echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
        exit();
    }

    // header("Location: ../skillList.php");
    //exit();

    function getSkills() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $query = $pdo->prepare('SELECT * FROM SKILL');
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
            echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
            exit();
        }

        
    }

    function getSkillsUser($emailUtente) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $query = $pdo->prepare("SELECT nomeskill, livello FROM CURRICULUM WHERE emailUtente = :email");
            $query->bindValue(":email", $emailUtente);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo("[ERRORE] Problemi di connessione con il database. Errore: " . $e->getMessage());
            echo '<a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>';
            exit();
        }
    }
?> 