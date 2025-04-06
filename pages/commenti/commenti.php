<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Commenti Completi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <div class="container">
        <h2 class="mb-4">Commenti con risposte</h2>

        <?php 
            include './commentiController.php';
            foreach ($completeComments as $item): ?>
            <?php
                $commento = $item->getCommento();     // Oggetto Commento
                $risposte = $item->getRisposte();     // Array di Risposta o null
            ?>
            <div style="position: absolute; top: 20px; right: 20px;">
                <a href="../home/home.php" class="btn btn-secondary">Home</a>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Commento di <?= htmlspecialchars($commento->getEmailUtente()) ?> sul progetto <strong><?= htmlspecialchars($commento->getNomeProgetto()) ?></strong>
                </div>
                <div class="card-body">
                    <p><strong>Testo:</strong> <?= htmlspecialchars($commento->getTesto()) ?></p>
                    <p><strong>Data:</strong> <?= htmlspecialchars($commento->getDataCommento()) ?></p>
                   

                    <?php if (!empty($risposte)): ?>
                        <div class="mt-3">
                            <h6>Risposte:</h6>
                            <ul class="list-group">
                                <?php foreach ($risposte as $risposta): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($risposta->getEmailUtenteCreatore()) ?>:</strong>
                                        <?= htmlspecialchars($risposta->getRisposta()) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Nessuna risposta disponibile.</p>
                        <?php 
                        session_start();
                        if (isset($_SESSION['email']) && isset($_COOKIE['creatore']) && $_SESSION['email'] == $_COOKIE['creatore']): ?>
                            <div class="mt-3">
                                <form action="commentiController.php" method="post">    
                                    <textarea class="form-control" placeholder="Digita risposta" name="risposta" id='risposta'></textarea>
                                    <input type="hidden" name="idCommento" id='idCommento' value="<?= htmlspecialchars($commento->getId()) ?>">
                                    <input type="hidden" name="emailUtenteCreatore" id="emailUtenteCreatore" value="<?= htmlspecialchars($_COOKIE['creatore']) ?>">
                                    <button type='submit' class="btn btn-primary mt-2">Invia</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
