<?php
function mostraErrore($titolo, $messaggio, $path) {
    ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Errore</title>
        <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8d7da;
                color: #721c24;
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .error-container {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 30px;
                border-radius: 10px;
                text-align: center;
                max-width: 600px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .btn-home {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h2> <?= htmlspecialchars($titolo) ?></h2>
            <p><strong>Dettagli:</strong> <?= htmlspecialchars($messaggio) ?></p>
            <a href="<?= htmlspecialchars($path) ?>" class="btn btn-danger btn-home">Torna alla Home</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}
