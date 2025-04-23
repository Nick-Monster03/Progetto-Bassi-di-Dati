<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
    <title>Registrazione</title>
</head>
<body class="p-5">
    <?php
        session_start();
        include('registerController.php');
    ?>

    <div class="container-fluid w-50 p-5" style="background-color: white;">
        <form action="registerController.php" method="post">
            <div class="logo-container text-center mb-3">
                <img src="/logo/bostarter_trasparente.png" alt="Logo" style="width: 150px; height:auto;">
            </div>
            <h1>SingIn</h1>
            <div class="form-group">
                <input type="email" id="email" name="email" class="form-control-custom" placeholder="Indirizzo email" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control-custom" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="text" id="nickname" name="nickname" class="form-control-custom" placeholder="Nickname" required>
            </div>
            <div class="form-group">
                <input type="text" id="nome" name="nome" class="form-control-custom" placeholder="Nome" required>
            </div>
            <div class="form-group">
                <input type="text" id="cognome" name="cognome" class="form-control-custom" placeholder="Cognome" required>
            </div>
            <div class="form-group">
                <input type="date" id="annoNascita" name="annoNascita" class="form-control-custom" placeholder="Anno di nascita" required>
            </div>
            <div class="form-group">
                <input type="text" id="luogoNascita" name="luogoNascita" class="form-control-custom" placeholder="Luogo di nascita" required>
            </div>
            <select id="userRole" name="userRole" required>
                <option value="utente">Utente</option>
                <option value="creatore">Creatore</option>
                <option value="amministratore">Amministratore</option>
            </select>
            <div class="text-center">
                <button><a href="../home/home.php">Torna alla Home</a></button>
                <button type="submit">Registrati</button>
            </div>
        </form>
    </div>
    <?php 
    if (isset($_SESSION['is_administrator'])): ?>
        <form method="post" action="registerController.php">
            <h2>Codice di Sicurezza Richiesto</h2>
            <label for="securityCode" class="form-label">Inserisci il codice di sicurezza:</label>
            <input type="password" class="form-control" id="securityCode" name="securityCode" required>
            <br>
            <button type="submit" class="btn btn-danger">Verifica</button>
        </form>
    <?php endif; ?>
</body>
</html>