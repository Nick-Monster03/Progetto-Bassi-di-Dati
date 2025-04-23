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