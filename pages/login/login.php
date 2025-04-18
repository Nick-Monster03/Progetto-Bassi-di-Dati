<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
    <?php
        session_start();
        require 'loginController.php';
    ?>

    <div class="container">
        <form action="login.php" method="post">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <img src="/logo/bostarter_trasparente.png" alt="Logo" style="width: 150px; height:auto;">
                <h1>Login</h1>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Inserisci la tua password" required>
            </div>
            <button type="submit">Login</button>
            <a href="../home/home.php" class="btn-home">Torna alla Home</a>
        </form>
    </div>

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'security_code') {
    ?>
        <form method="POST" action="login.php" class="form-margin">
            <h2>Codice di Sicurezza Richiesto</h2>
            <label for="security_code" class="form-label">Inserisci il codice di sicurezza:</label>
            <input type="password" id="security_code" name="security_code" required>
            <br>
            <button type="submit" class="btn btn-danger">Verifica</button>
        </form>
    <?php
    }
    ?>
</body>
</html>