<?php

    session_start();
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        logout();
    }
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }
    $query = $pdo->prepare('SELECT * FROM Progetto');
    $query->execute();
    $projects = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $column => $value) {
            $projects[$column][] = $value;
        }
    }
    $GLOBALS['projects'] = $projects;

    function logout() {
        header("Location: ../login/login.php");
        $_SESSION=[];
        session_destroy();
        header("Location: ../home/home.php");
    }
?>