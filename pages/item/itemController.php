<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
    exit();
}

if (isset($_GET['nome'])) {
    $nomeProgetto = htmlspecialchars($_GET['nome']);
    $query = $pdo->prepare('SELECT * FROM Progetto WHERE nome = :nomeProgetto');
    $query->bindValue(':nomeProgetto', $nomeProgetto);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        foreach ($result as $row) {
            echo "<div class='card mb-4'>";
            echo "<div class='card-body'>";
            echo "<h2 class='card-title'>Dettagli del Progetto</h2>";
            foreach ($row as $column => $value) {
                echo "<p class='card-text'><strong>$column:</strong> $value</p>";
            }
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-center'>Nessun progetto trovato con il nome '$nomeProgetto'.</p>";
    }
} else {
    echo "<h1 class='text-center'>Benvenuto!</h1>";
}
?>