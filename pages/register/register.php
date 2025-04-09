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
    <form action="registerController.php" method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password"  name="password">
        </div>
        <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>
            <input type="text" class="form-control" id="nickname" name="nickname">
        </div>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" class="form-control" id="cognome" name="cognome">
        </div>
        <div class="mb-3">
            <label for="annoNascita" class="form-label">Anno di Nascita</label>
            <input type="date" class="form-control" id="annoNascita" name="annoNascita">
        </div>
        <div class="mb-3">
            <label for="luogoNascita" class="form-label">Luogo di Nascita</label>
            <input type="text" class="form-control" id="luogoNascita" name="luogoNascita">
        </div>
        <div class="mb-3 ">
            <label for="userRole" class="form-label">Seleziona il tuo ruolo</label>
            <select class="form-select" id="userRole" name="userRole">
                <option value="utente">Utente</option>
                <option value="creatore">Creatore</option>
                <option value="amministratore">Amministratore</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>
</html>