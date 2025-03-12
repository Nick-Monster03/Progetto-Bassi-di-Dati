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
    <a href="home.php">&lt;Home</a>
    <form action='login.php' method="post">
        <br>
        <br>
        <hr> LOGIN </hr>
        <table>
            <tr>
                <td><b>Specifica che ruolo ricopri</b></td>
                <td>
                    <select name="role" id="role">
                        <option value="utente" selected>Utente</option>
                        <option value="creatore">Creatore</option>
                        <option value="amministratore">Amministratore</option>
                    </select>
                </td>
            </tr>
            <tr>
            <td><b>Inserisci la tua email:</b></td>
            <td><input type='text' name="email" id="email"></td>
            </tr>
        </table>
        <div><input type='submit' value='Login'></div>
    </form>
    <br>
    <?php
    session_start();
    if (isset($_GLOBALS['check_security_code']) && $_GLOBALS['check_security_code']== 1) {
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
