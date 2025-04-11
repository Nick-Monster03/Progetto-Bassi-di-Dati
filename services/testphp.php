<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questa pagina serve solo per visualizzare il database relazionale e quello dei movimenti</title>
</head>
<body>
<?php
try {
    // Connessione al database tramite PDO
    $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gestione errore connessione
    echo "[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage();
    exit();
}

// Funzione per visualizzare i dati di una tabella in HTML
function displayTable($pdo, $query, $tableName) {
    // Esegui la query
    $result = $pdo->query($query);

    if ($result->rowCount() > 0) {
        echo "<h2>Tabella: $tableName</h2>";
        echo "<table border='1'>";
        echo "<tr>";

        // Stampa le intestazioni della tabella
        $columns = $result->fetch(PDO::FETCH_ASSOC);  // Prendi la prima riga per le intestazioni
        if ($columns) {
            foreach ($columns as $columnName => $value) {
                echo "<th>" . htmlspecialchars($columnName) . "</th>";
            }
            echo "</tr>";

            // Ora stampa tutte le righe
            // Riporta il cursore all'inizio
            $result->closeCursor();
            $result = $pdo->query($query);  // Esegui nuovamente la query per avere il cursore all'inizio

            // Stampa le righe
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                foreach ($row as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table><br>";
        }
    } else {
        echo "0 risultati per la tabella $tableName.<br>";
    }
}

// Esegui la funzione per ogni tabella
displayTable($pdo, "SELECT * FROM UTENTE", "UTENTE");
displayTable($pdo, "SELECT * FROM SKILL", "SKILL");
displayTable($pdo, "SELECT * FROM CURRICULUM", "CURRICULUM");
displayTable($pdo, "SELECT * FROM AMMINISTRATORE", "AMMINISTRATORE");
displayTable($pdo, "SELECT * FROM CREATORE", "CREATORE");
displayTable($pdo, "SELECT * FROM PROGETTO", "PROGETTO");
displayTable($pdo, "SELECT * FROM FOTO", "FOTO");
displayTable($pdo, "SELECT * FROM REWARD", "REWARD");
displayTable($pdo, "SELECT * FROM PROGETTO_HARDWARE", "PROGETTO_HARDWARE");
displayTable($pdo, "SELECT * FROM PROGETTO_SOFTWARE", "PROGETTO_SOFTWARE");
displayTable($pdo, "SELECT * FROM COMPONENTE", "COMPONENTE");
displayTable($pdo, "SELECT * FROM COMPONENTI_PROGETTO", "COMPONENTI_PROGETTO");
displayTable($pdo, "SELECT * FROM PROFILO", "PROFILO");
displayTable($pdo, "SELECT * FROM PROFILO_SKILL", "PROFILO_SKILL");
displayTable($pdo, "SELECT * FROM FINANZIAMENTO", "FINANZIAMENTO");
displayTable($pdo, "SELECT * FROM COMMENTO", "COMMENTO");
displayTable($pdo, "SELECT * FROM RISPOSTA", "RISPOSTA");
displayTable($pdo, "SELECT * FROM CANDIDATURA", "CANDIDATURA");
displayTable($pdo, "SELECT * FROM Top3Finanziatori", "Top3Finanziatori");
displayTable($pdo, "SELECT * FROM Top3ProgettiVicinoScadenza", "Top3ProgettiVicinoScadenza");
displayTable($pdo, "SELECT * FROM Top3Creatori", "Top3Creatori");


require 'vendor/autoload.php';  // Assicurati che 'vendor/autoload.php' esista nella tua cartella di progetto

try {
    // Crea il client MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    // Seleziona il database
    $db = $client->Movimenti;
    echo "<h2>LOG EVENTI MongoDB</h2>";

    $collections = $db->listCollections();

    foreach ($collections as $collectionInfo) {
        $collectionName = $collectionInfo->getName();
        echo "<h3>Collezione: <strong>" . htmlspecialchars($collectionName) . "</strong></h3>";

        $collection = $db->$collectionName;
        $documents = $collection->find();

        $firstRow = true;
        echo "<table border='1'>";

        foreach ($documents as $document) {
            // Intestazioni solo la prima volta
            if ($firstRow) {
                echo "<tr>";
                foreach ($document as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
                echo "</tr>";
                $firstRow = false;
            }

            // Riga dati
            echo "<tr>";
            foreach ($document as $key => $value) {
                echo "<td>" . htmlspecialchars(is_object($value) ? json_encode($value) : $value) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table><br>";
    }
} catch (Exception $e) {
    echo "Errore nella connessione a MongoDB: ", $e->getMessage();
}
?>

</body>
</html>
