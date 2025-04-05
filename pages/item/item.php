<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Progetto</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <header>
        <div class="container d-flex flex-column align-items-center py-3">
            <h1 class="titleWebSite">Bostarter</h1>
        </div>
    </header>
    <div class="container mt-5">
        <div class="text-center mt-4">
            <button class="btn btn-secondary" onclick="window.history.back()">Torna Indietro</button>
        </div>
    </div>
    <?php
        session_start();
        include 'itemController.php';
    ?>
    <?php if ($stato == "aperto" && $creatore != $_SESSION["email"]):?>
    <a href='../finanziamento/finanziamento.php'>Finanzia progetto</a><br>
    <?php elseif ($creatore != $_SESSION["email"]):?>
        <h5>Progetto chiuso</h5>
        <p>
            <label style='color: gray;'>
                Il progetto risulta chiuso, quindi non puoi effettuare finanziamenti.
            </label>
        </p>
    <?php endif; ?>

    <label>Budget attuale: €<?= number_format($valoreAttuale, 2, ',', '.') ?></label>
    <br>
    <?php 
        session_start();
        if ($creatore != $_SESSION["email"] && $stato == "aperto"): 
    ?>
     <a href="../richiestaCandidatura/richiestaCandidatura.php">Lavora Per questo progetto</a>

    <?php endif; ?>
    <a href="../commenti/commenti.php">Visualizza Commenti</a>
    <?php if ($stato == "aperto"): ?>
    <a href="../reward/reward.php">Visualizza Reward</a>
    <?php endif; ?>


    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>

</body>
</html>