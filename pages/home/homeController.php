<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connessione al DB non riuscita. Errore: " . $e->getMessage());
}

$projects = [];
try {
    $res = $pdo->query("SELECT nome FROM Progetto");
    $projects = $res->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query SQL non riuscita. Errore: " . $e->getMessage());
}

$GLOBALS['projects'] = $projects;

if (isset($_GET['action']) && $_GET['action'] == 'search') {
    $query = $_GET['query'];
    try {
        $res = $pdo->prepare("SELECT nome FROM Progetto WHERE nome LIKE :query LIMIT 10");
        $res->bindValue(":query", "%$query%");
        $res->execute();
        $results = $res->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Query SQL non riuscita. Errore: " . $e->getMessage()]);
    }
    exit();
}
?>