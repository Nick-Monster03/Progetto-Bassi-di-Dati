<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina 2</title>
</head>
<body>
    <p>Benvenuto a pagina 2</p>
    <a href="index.php">Vai a Index</a>
    <?php
        session_start(); // Start the session
        $res = isset($_SESSION['res']) ? $_SESSION['res'] : 0; // Retrieve the variable $res from the session
        echo ("<b> Totale Righe Inserite nel DB: ".$res . "</b><br>");
    ?>
</body>
</html>