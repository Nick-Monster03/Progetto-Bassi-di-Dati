<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Commenti Completi</title>
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">

    <div class="container">
        <h2 class="mb-4">Commenti con risposte</h2>
        
        <?php 
        session_start();
        if (isset($_SESSION['email']) && isset($_COOKIE['creatore']) && $_SESSION['email'] != $_COOKIE['creatore']): ?> 
        <!-- Se il coockie dovessere scaduto e quindi non c' è $_COOCKIE['creatore'] l' eccezioe sarebbe lanciata
        Successivamente una volta inclusa la pagina commentiController.php, -->
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentModal">+ Aggiungi Commento</a>
        <?php endif; ?>
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="commentiController.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Aggiungi un commento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="testo" class="form-label">Testo del commento</label>
                            <textarea class="form-control" id="testoCommento" name="testoCommento" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                        <button type="submit" class="btn btn-success">Invia Commento</button>
                    </div>
                </form>
            </div>
        </div>  
        <div style="position: absolute; top: 20px; right: 20px;">
            <a href="../item/item.php?nome=<?= urlencode($_COOKIE['nomeProgetto'] ?? '') ?>" class="btn btn-secondary">Torna indietro</a>
        </div>
        <?php 
            include './commentiController.php';
            
            foreach ($completeComments as $item): ?>
            <?php
                $commento = $item->getCommento();     // Oggetto Commento
                $risposta = $item->getRisposte(); 
            ?>
            

            <div style="position: absolute; top: 20px; right: 20px;">
                <a href="../item/item.php?nome=<?= urlencode($commento->getNomeProgetto()) ?>" class="btn btn-secondary">Torna indietro</a>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Commento di <?= htmlspecialchars($commento->getEmailUtente()) ?> sul progetto <strong><?= htmlspecialchars($commento->getNomeProgetto()) ?></strong>
                </div>
                <div class="card-body">
                    <p><strong>Testo:</strong> <?= htmlspecialchars($commento->getTesto()) ?></p>
                    <p><strong>Data:</strong> <?= htmlspecialchars($commento->getDataCommento()) ?></p>
                   

                    <?php if (!empty($risposta)): ?>
                        <div class="mt-3">
                            <h6>Risposte:</h6>
                            <ul class="list-group">
                                
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($risposta->getEmailUtenteCreatore()) ?>:</strong>
                                        <?= htmlspecialchars($risposta->getRisposta()) ?>
                                    </li>
                               
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Nessuna risposta disponibile.</p>
                        <?php 
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
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
