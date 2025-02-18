<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="home.css">
</head>
<?php
    session_start();
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $_SESSION['pdo'] = $pdo;
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }

    $query = $pdo->query('SELECT * FROM Progetto');
    echo "<table border='1'>";
    echo "<tr><th>Nome</th><th>Descrizione</th><th>Data Inserimento</th><th>Budget</th><th>Data Limite</th><th>Stato</th><th>Email Utente Creatore</th></tr>";
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['descrizione']) . "</td>";
        echo "<td>" . htmlspecialchars($row['data_inserimento']) . "</td>";
        echo "<td>" . htmlspecialchars($row['budget']) . "</td>";
        echo "<td>" . htmlspecialchars($row['data_limite']) . "</td>";
        echo "<td>" . htmlspecialchars($row['stato']) . "</td>";
        echo "<td>" . htmlspecialchars($row['emailUtenteCreatore']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
?>
<a href="creatore.php">Clicca qui per andare alla pagina creatore</a>

<body>
</body>
</html>