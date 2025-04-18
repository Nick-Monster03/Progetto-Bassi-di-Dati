<?php 
include 'competenzeController.php'; // carica $competenze
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competenze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../competenze/competenze.css">
</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body>
    <div class="container">
        <a href="../home/home.php" class="btn-home">Torna alla Home</a>
                <div class="header">
                    <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
                    <h1>Competenze disponibili</h1>
                </div>
        <div class="skills-container">
            <?php foreach ($competenze as $skill): ?>
                <div class="card">
                    <h5 class="card-title"><?= htmlspecialchars($skill['nome']) ?></h5>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="actions">
            <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalAggiungi">+ Aggiungi competenza</a>
        </div>
    </div>

    <!-- Modal per aggiungere competenze -->
    <div class="modal" id="modalAggiungi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="competenzeController.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserisci nuova competenza</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="nuovaCompetenza" class="form-control" maxlength="25" required placeholder="Es: PHP">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Aggiungi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>