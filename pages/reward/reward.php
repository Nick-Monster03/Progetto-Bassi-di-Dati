<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .reward-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .reward-card {
            border: 1px solid #ccc;
            padding: 15px;
            width: 250px;
        }
        .reward-card img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <?php
    include('rewardController.php');
    ?>
    <?php  
    if (!empty($rewards)): ?>
        <h2>Reward disponibili per il progetto "<?= htmlspecialchars($nomeProgetto) ?>"</h2>
        <div class="reward-container">
            <?php foreach ($rewards as $r): ?>
                <div class="reward-card">
                    <h4>Codice: <?= htmlspecialchars($r['codice']) ?></h4>
                    <p><?= htmlspecialchars($r['descrizione']) ?></p>
                    <p><?= htmlspecialchars($r['foto']) ?></p>
                    <?php if (!empty($r['foto'])): ?>
                        <img src="../../services/uploads/<?= htmlspecialchars($r['foto']) ?>" alt="reward image">
                    <?php else: ?>
                        <p><em>Nessuna immagine disponibile</em></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nessun reward disponibile al momento.</p>
    <?php endif; ?>
    <div class="actions">
        <button onclick="window.location.href='../home/home.php'">Torna alla Home</button>
        <button onclick="window.location.href='../item/item.php?nome=<?= urlencode($nomeProgetto) ?>'">Torna Indietro</button>
    </div>
    <?php
        session_start();
        //la casistica in cui il coockie sia scaduto è già gestita nel controller, quindi non serve fare un controllo anche qui
        if($_COOKIE["creatore"] == $_SESSION["email"]){
            echo "<a href='./newReward/newReward.php'>Aggiungi un nuovo reward</a>";
        }
    ?>
</body>
</html>

