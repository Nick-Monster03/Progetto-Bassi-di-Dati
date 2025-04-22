<?php
session_start();

//svuoto i coocke che mi sono serviti per interagire con il progetto in precedenza
if(isset($_COOKIE["creatore"]) || isset($_COOKIE["nomeProgetto"]) || isset($_COOKIE["valoreAttuale"])){
    setcookie("nomeProgetto", "", time() - 3600, "/");
    setcookie("creatore", "", time() - 3600, "/");
    setcookie("valoreAttuale", "", time() - 3600, "/");
}
try {
    $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connessione al DB non riuscita. Errore: " . $e->getMessage());
}
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    exit(); 
}
$projects = [];
try {
    $query = "SELECT nome FROM Progetto";
    $res = $pdo->prepare($query);
    $res->execute();
    $projects = $res->fetchAll(PDO::FETCH_ASSOC);

    // Query per Top3Creatori
    $stmt1 = $pdo->prepare("SELECT * FROM Top3Creatori");
    $stmt1->execute();
    $Top3Creatori = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Query per Top3ProgettiVicinoScadenza
    $stmt2 = $pdo->prepare("SELECT * FROM Top3ProgettiVicinoScadenza");
    $stmt2->execute();
    $Top3ProgettiVicinoScadenza = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Query per top3finanziatori
    $stmt3 = $pdo->prepare("SELECT * FROM top3finanziatori");
    $stmt3->execute();
    $top3finanziatori = $stmt3->fetchAll(PDO::FETCH_ASSOC);
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

function logout() {
    $_SESSION=[];
    session_destroy();
    header("Location: ../home/home.php");
}
?>