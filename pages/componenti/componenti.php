<?php
// Assumendo che $componenti sia già popolato dalla query SQL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Componenti Progetto</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php 
    include 'componentiController.php';
    session_start();
    if (isset($_COOKIE['nomeProgetto'])): ?>
        <h1>Lista Componenti - <?= htmlspecialchars($_COOKIE['nomeProgetto']) ?></h1>
        <?php if (!isset($_SESSION['creation_phase'])): ?>
            <a href="../item/item.php?nome=<?= urlencode($_COOKIE['nomeProgetto']) ?>">Torna Indietro</a><br><br>
        <?php endif; ?>
    <?php endif; ?>

    <?php 
    if (isset($_SESSION['email'], $_COOKIE['creatore']) && $_SESSION['email'] == $_COOKIE['creatore']): ?>
        <form action="componentiController.php" method="post">
            <label for="nomeComponente">Nome Componente:</label>
            <input type="text" id="nomeComponente" name="nomeComponente" maxlength="40" required><br><br>

            <label for="descrizione">Descrizione:</label>
            <textarea id="descrizione" name="descrizione" maxlength="300" required></textarea><br><br>

            <label for="prezzo">Prezzo:</label>
            <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" required><br><br>

            <label for="quantita">Quantità:</label>
            <input type="number" id="quantita" name="quantita" min="1" required><br><br>

            <button type="submit">Aggiungi Componente</button>
        </form>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Nome Componente</th>
                <th>Prezzo</th>
                <th>Quantità</th>
                <th>Descrizione</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
            if (!empty($componenti)): ?>
                <?php foreach ($componenti as $componente): ?>
                    <tr>
                        <td><?= htmlspecialchars($componente['nome']) ?></td>
                        <td><?= htmlspecialchars($componente['prezzo']) ?> €</td>
                        <td><?= htmlspecialchars($componente['quantita']) ?></td>
                        <td><?= htmlspecialchars($componente['descrizione']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nessun componente trovato.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if (isset($_SESSION['creation_phase']) && $_SESSION['creation_phase'] == 1): ?>
        <a href="../reward/newReward/newReward.php">Seleziona Reward</a>
    <?php endif; ?>
</body>
</html>