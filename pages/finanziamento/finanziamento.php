<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Finanzia Progetto</title>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
        try{
            if (!isset($_COOKIE['nomeProgetto']) || !isset($_COOKIE['valoreAttuale'])) {
                throw new Exception('SESSIONE SCADUTA. <a href="../home/home.php">backHome</a>');
            }
    
            $nomeProgetto = $_COOKIE['nomeProgetto'];
            $budgetAttuale = $_COOKIE['valoreAttuale'];
        }catch(Exception $e){
            echo 'Errore: ' . $e->getMessage();
            echo '<a href="../item/item.php">Torna alla pagina del progetto</a>';
        }
        include('./finanziamentoController.php');
        
    ?>
    <h2>Budget attuale: € <?= number_format($budgetAttuale, 2, ',', '.') ?></h2>

    <form action="finanziamentoController.php" method="POST">


        <label for="importo">Importo (€):</label><br>
        <input type="number" id="importo" name="importo" step="0.01" min="0.01" required><br><br>

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
                    <?php if (!empty($r['foto'])): ?>
                        <img src="<?= htmlspecialchars('../../services/uploads/' . $r['foto']) ?>" alt="reward image">
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
