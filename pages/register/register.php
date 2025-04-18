<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="register.css">
    <title>Registrazione</title>
</head>
<body>
    <?php
        session_start();
        include('registerController.php');
    ?>


    <div class="container">
        <form action="registerController.php" method="post">
             <a href="../home/home.php" class="btn-home">Torna alla Home</a>
             <div class="header">
            <div style="display: flex; flex-direction: column; align-items: center;">
                       <img src="/logo/bostarter_trasparente.png" alt="Logo" style="width: 150px; height:auto;">
                       <h1>Registrati</h1>
                   </div>
               </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Inserisci la tua password" required>
            </div>
            <div class="form-group">
                <label for="nickname">Nickname</label>
                <input type="text" id="nickname" name="nickname" placeholder="Inserisci il tuo nickname" required>
            </div>
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Inserisci il tuo nome" required>
            </div>
            <div class="form-group">
                <label for="cognome">Cognome</label>
                <input type="text" id="cognome" name="cognome" placeholder="Inserisci il tuo cognome" required>
            </div>
            <div class="form-group">
                <label for="annoNascita">Anno di Nascita</label>
                <input type="date" id="annoNascita" name="annoNascita" required>
            </div>
            <div class="form-group">
                <label for="luogoNascita">Luogo di Nascita</label>
                <input type="text" id="luogoNascita" name="luogoNascita" placeholder="Inserisci il tuo luogo di nascita" required>
            </div>
            <div class="form-group">
                <label for="userRole">Ruolo</label>
                <select id="userRole" name="userRole" required>
                    <option value="utente">Utente</option>
                    <option value="creatore">Creatore</option>
                    <option value="amministratore">Amministratore</option>
                </select>
            </div>
            <button type="submit">Registrati</button>
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