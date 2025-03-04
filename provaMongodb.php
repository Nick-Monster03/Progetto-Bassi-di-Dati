<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal PHP and HTML</title>
</head>
<body>
    <h1>Welcome to My Minimal PHP Page</h1>
    <?php
// Includi l'autoloader di Composer
require 'vendor/autoload.php';  // Assicurati che 'vendor/autoload.php' esista nella tua cartella di progetto

// Mostra errori per il debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connessione a MongoDB
try {
    // Crea il client MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");

    // Verifica se la connessione a MongoDB è andata a buon fine
    $databases = $client->listDatabases();
    echo "Connessione riuscita! Database disponibili:\n";
    foreach ($databases as $database) {
        echo $database['name'] . "<br>";
    }

    // Seleziona il database
    $db = $client->TennisBO;
    echo "Connessione al database 'TennisBO' riuscita.<br>";

    // Seleziona la collezione
    $collection = $db->Soci;

    // Trova tutti i documenti nella collezione
    $documents = $collection->find();

    // Stampa tutti i documenti
    echo "Documenti trovati nella collezione 'circoli':<br>";
    foreach ($documents as $document) {
        echo '<pre>';
        print_r($document);
        echo '</pre>';
    }

} catch (Exception $e) {
    echo "Errore nella connessione a MongoDB: ", $e->getMessage();
}
?>


</body>
</html>