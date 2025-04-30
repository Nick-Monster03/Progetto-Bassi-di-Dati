<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Finanzia Progetto</title>
    <style>
    body {
      font-family: 'Cinzel', serif;
      background-color: #0a899a;
      margin: 0;
      padding: 40px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      color: white;
      font-size: 2rem;
      margin-bottom: 20px;
      margin-top: 0;
    }

    form {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: left;
      margin-bottom: 30px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
      color: #0a899a;
      font-size: 1.1rem;
    }

    input[type="number"], select {
      width: 100%;
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    button {
      background-color: #0a899a;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #1aa9b2;
    }

    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    a {
      background-color: white;
      padding: 10px;
      border-radius: 5px;
      display: inline-block;
      color: #0a899a;
      font-weight: bold;
      text-decoration: none;
      margin-bottom: 30px;
    }

    a:hover {
      background-color: whitesmoke;
    }

    .reward-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 30px;
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

    p {
      font-size: 1rem;
      color: #555;
    }

    @font-face {
      font-family: 'Cinzel';
      src: url('./font/Cinzel-Regular.ttf') format('truetype');
    }

    @media (max-width: 500px) {
      form {
        padding: 20px;
      }

      h2 {
        font-size: 1.7rem;
      }
    }
  </style>
</head>
<body>
    <?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    include('../../services/mostraErrore.php');
        try{
            if (!isset($_COOKIE['nomeProgetto']) || !isset($_COOKIE['valoreAttuale'])) {
                throw new Exception('SESSIONE SCADUTA. Torna indietro.');
            }
    
            $nomeProgetto = $_COOKIE['nomeProgetto'];
            $budgetAttuale = $_COOKIE['valoreAttuale'];
        }catch(Exception $e){
            echo 'Errore: ';
            mostraErrore($e->getCode(), $e->getMessage(), '../home/home.php');
            exit();
        }
        include('./finanziamentoController.php');
        
    ?>
    <h2>Budget attuale: € <?= number_format($budgetAttuale, 2, ',', '.') ?></h2>

    <form action="finanziamentoController.php" method="POST">
        <label for="importo">Importo (€):</label>
        <input type="number" id="importo" name="importo" step="0.01" min="0.01" required>
        <label >Seleziona un reward:</label>
        <select id="id_reward" name="id_reward" required>
            <?php foreach ($rewards as $r): ?>
            <option value="<?= htmlspecialchars($r['codice']) ?>"><?= htmlspecialchars($r['codice']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" <?= $flag_finanziamento==true ? '' : 'disabled'  ?>>Finanzia</button>
        <label><?=$motivazione?></label>
    </form>
    <a href="../item/item.php?nome=<?= urlencode($nomeProgetto) ?>">Torna alla pagina del progetto</a>
    <?php  
    if (!empty($rewards)): ?>
        <h2 style="color: white; margin-bottom: 0;">Reward disponibili per il progetto "<?= htmlspecialchars($nomeProgetto) ?>"</h2>
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
</body>
</html>
