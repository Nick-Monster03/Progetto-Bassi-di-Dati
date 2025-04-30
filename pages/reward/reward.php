<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards</title>
    <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #0a899a;
      margin: 0;
      padding: 20px;
      text-align: center;
    }

    h2 {
      color: #333;
      margin-bottom: 30px;
      color: white;
    }

    .reward-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .reward-card {
      background: white;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      width: 250px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .reward-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .reward-card img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin-top: 10px;
    }

    .reward-card h4 {
      color: #444;
      margin-bottom: 10px;
    }

    .reward-card p {
      color: #666;
      font-size: 14px;
    }

    .actions {
      margin-top: 30px;
    }

    .actions button {
      background-color: white;
      border: none;
      color: #0a899a;
      padding: 12px 20px;
      margin: 5px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.2s;
    }

    .actions button:hover {
      background-color: whitesmoke;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      color: #0a899a;;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
    <?php
    include('rewardController.php');
    session_start();
    ?>
    <?php  
    if (!empty($rewards)): ?>
        <h2>Reward disponibili per il progetto "<?= htmlspecialchars($nomeProgetto) ?>"</h2>
        <div class="reward-container">
            <?php foreach ($rewards as $r): ?>
                <div class="reward-card">
                    <h4>Codice: <?= htmlspecialchars($r['codice']) ?></h4>
                    <p><?= htmlspecialchars($r['descrizione']) ?></p>
                    <?php if (!empty($r['foto'])): 
                        $imageData = base64_encode($r['foto']);
                        $src = 'data:image/jpeg;base64,' . $imageData;
                    ?>
                    <img src="<?= $src ?>" alt="Reward Image">
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        //la casistica in cui il coockie sia scaduto è già gestita nel controller, quindi non serve fare un controllo qui
        //se il coockie esista o meno
        if($_COOKIE["creatore"] == $_SESSION["email"]){
            echo "<a href='./newReward/newReward.php'>+ Aggiungi un nuovo reward</a>";
        }
    ?>
</body>
</html>

