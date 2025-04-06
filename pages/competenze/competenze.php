<?php 
include 'competenzeController.php'; // carica $competenze
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Competenze</title>
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">

    

    <!-- Lista delle competenze -->
    <h2>Competenze disponibili</h2>
    <div class="mb-3">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAggiungi">
            + Aggiungi competenza
        </a>
    </div>
    <ul class="list-group w-50">
        <?php foreach ($competenze as $skill): ?>
            <li class="list-group-item"><?= htmlspecialchars($skill['nome']) ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Bottone per tornare alla home -->
    <div style="position: absolute; top: 20px; right: 20px;">
        <a href="../home/home.php" class="btn btn-secondary">Home</a>
    </div>
    <div class="modal fade" id="modalAggiungi" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="competenzeController.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Inserisci nuova competenza</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="nuovaCompetenza" id ="nuovaCompetenza" class="form-control" maxlength="25" required placeholder="Es: PHP">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Aggiungi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
