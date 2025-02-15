<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="progetto.css">
</head>
<body>


<?php
    include('stampa.php');

?>

<form method="GET" >
    <label for="tipo">Tipo:</label>
    <input type="text" id="tipo" name="tipo" required><br><br>
    <label for="descrizione">Descrizione:</label>
    <input type="text" id="descrizione" name="descrizione" required><br><br>
    <label for="importo">Importo:</label>
    <input type="number" id="importo" name="importo" step="0.01" required><br><br>
    <input type="submit" value="Invia">
</form>
</body>
</html>