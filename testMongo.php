<?php
require 'vendor/autoload.php'; // Assicurati di avere il composer autoload

$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->test;
$collection = $database->testCollection;

echo "Connected to MongoDB successfully!";
?>
