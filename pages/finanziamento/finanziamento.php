<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Finanzia Progetto</title>
</head>
<body>
    <?php
        try{
            if (!isset($_COOKIE['nomeProgetto']) || !isset($_COOKIE['valoreAttuale'])) {
                throw new Exception('SESSIONE SCADUTA. <a href="../home/home.php">backHome</a>');
            }
            $nomeProgetto = $_COOKIE['nomeProgetto'];
            $budgetAttuale = $_COOKIE['valoreAttuale'];
        }catch(Exception $e){
            echo 'Errore: ' . $e->getMessage();
        }
        include('./finanziamentoController.php');
        
    ?>
    <h2>Budget attuale: € <?= number_format($budgetAttuale, 2, ',', '.') ?></h2>
    <div style="position: absolute; top: 10px; right: 10px;">
        <a href="../item/item.php?nome=<?= urlencode($nomeProgetto) ?>" style="text-decoration: none; padding: 10px 20px; background-color: #007BFF; color: white; border-radius: 5px;">Torna indietro</a>
    </div>
    <form action="finanziamentoController.php" method="POST">
        <div>
            <label for="importo">Importo (€):</label><br>
            <input type="number" id="importo" name="importo" step="0.01" min="0.01" required><br><br>
        </div>
        <div>
            <label>Inserisci il codice del reward che più ti piace:</label><br>
            <label>(ATTENZIONE: se inserisci il codice di un reward che non è presente in questa schermata allora non riceverai nulla)</label><br>
            <input type="number" id="id_reward" name="id_reward" step="1"><br><br>
        </div>
        <button type="submit" <?= $flag_finanziamento==true ? '' : 'disabled'  ?>>Finanzia</button>
        <label><?=$motivazione?></label>
    </form>
    <br>

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
                        <img src="<?= htmlspecialchars($src) ?>" alt="reward image">
                    <?php else: ?>
                        <p><em>Nessuna immagine disponibile</em></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nessun reward disponibile al momento.</p>
    <?php endif; ?>
    <br>
    <a href="../item/item.php?nome=<?= urlencode($nomeProgetto) ?>">Torna alla pagina del progetto</a>
</body>
</html>
