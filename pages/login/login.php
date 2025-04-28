<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../register/register.css">
    <title>Login</title>
</head>
<body class="p-5">
    <?php
        session_start();
        require 'loginController.php';
    ?>
    <?php if (isset($_COOKIE['credential_flag']) && $_COOKIE['credential_flag'] == 'false'): ?>
        <p class="alert alert-danger" role="alert" style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Credenziali errate o utente non registrato, riprova</p>
    <?php endif; ?>

    <?php if (isset($_COOKIE['security_code_flag']) && $_COOKIE['security_code_flag'] == 'false'): ?>
        <p style='color: darkred; background-color: lightcoral; opacity: 0.8; width: 16%; margin: 20px auto; text-align: center;'>Codice di sicurezza non valido</p>
    <?php endif; ?>
    <?php
    if (!isset($_SESSION['status'])){
    ?>
    <div class="container-fluid w-25 p-5" style="background-color: white;">
        <form action="login.php" method="post">
            <div class="logo-container text-center mb-3">
                <img src="/logo/bostarter_trasparente.png" alt="Logo" style="width: 150px; height:auto;">
            </div>
            <h1>LogIn</h1>
            <div class="form-group">
                <input class="form-control-custom" type="text" id="email" name="email" placeholder="Indirizzo email" required>
            </div>
            <div class="form-group">
                <input class="form-control-custom" type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="text-center">
                <button><a href="../home/home.php">Torna alla Home</a></button>
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
    <?php
    }
    ?>
    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'security_code') {
    ?>
        <div class="container-fluid w-25 p-5" style="background-color: white;">
            <form method="POST" action="login.php" class="form-margin">
                <h5>Codice di Sicurezza Richiesto</h5>
                <div class="form-group">
                    <input class="form-control-custom" type="password" id="security_code" name="security_code" placeholder="Inserisci il codice di sicurezza:" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Verifica</button>
                </div>
            </form>
        </div>
    <?php
    }
    ?>
</body>
</html>