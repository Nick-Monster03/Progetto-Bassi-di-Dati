<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
</head>
<body>
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
    <?php if ($stato == "aperto"): ?>
    <a href="../reward/reward.php">Visualizza Reward</a>
    <?php endif; ?>

</body>
</html>