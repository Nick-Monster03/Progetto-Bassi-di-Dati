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
        <a href="../item/item.php?nome=<?= urlencode($_COOKIE['nomeProgetto']) ?>">Torna Indietro</a><br><br>
    <?php endif; ?>

    <?php 
    if (isset($_SESSION['email'], $_COOKIE['creatore']) && $_SESSION['email'] == $_COOKIE['creatore']): ?>
        <form action="componentiController.php" method="post">
            <label for="nomeComponente">Aggiungi un nuovo componente:</label><br><br>
            <label for="nomeComponente">Nome Componente:</label>
            <select id="nomeComponente" name="nomeComponente" required>
               
                <?php 
                     $componentiInutilizzati= getComponentiInutilizzati($_COOKIE['nomeProgetto']);
                    foreach ($componentiInutilizzati as $componente): ?>
                        <option value="<?= htmlspecialchars($componente['nome']) ?>">
                            <?= htmlspecialchars($componente['nome']) ?>
                        </option>
                <?php endforeach; ?>
            </select><br><br>
            <label for="quantita">Quantità:</label>
            <input type="number" id="quantita" name="quantita" min="1" required><br><br>
            <button type="submit">Aggiungi</button>
        </form>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Nome Componente</th>
                <th>Prezzo</th>
                <th>Quantità</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
            if (!empty($componenti)): ?>
                <?php foreach ($componenti as $componente): ?>
                    <tr>
                        <td><?= htmlspecialchars($componente['nomeComponente']) ?></td>
                        <td><?= htmlspecialchars($componente['prezzo']) ?> €</td>
                        <td><?= htmlspecialchars($componente['quantita']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nessun componente trovato.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>