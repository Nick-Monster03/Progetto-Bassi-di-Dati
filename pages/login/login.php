<?php
    session_start();
    require 'loginController.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <a href="../home/home.php">&lt;Home</a>
    <form action='login.php' method="post">
        <br>
        <br>
        <hr> LOGIN </hr>
        <table>
            <tr>
            <td><b>Inserisci la tua email:</b></td>
            <td><input type='text' name="email" id="email"></td>
            <td><b>Inserisci la tua password:</b></td>
            <td><input type='password' name="password" id="password"></td>
            </tr>
        </table>
        <div><input type='submit' value='Login'></div>
    </form>
    <br>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'security_code') {       
    ?>
        <br>
        <form method="POST" action="login.php">
            <label for="security_code">Codice di Sicurezza:</label>
            <input type="password" id="security_code" name="security_code" required>
            <button type="submit">Invia</button>
        </form>
    <?php
    }
    ?>
</body>
</html>
