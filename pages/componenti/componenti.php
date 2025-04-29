<?php
// Assumendo che $componenti sia già popolato dalla query SQL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Componenti Progetto</title>
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/pages/home/home.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php 
    include 'componentiController.php';
    session_start();
    if (isset($_COOKIE['nomeProgetto'])): ?>
        <div class="conteiner text-center mt-2">
            <a class="navbar-brand" href="/pages/home/home.php"><img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo w-25"></a>
        </div>
        <h1 class="text-center m-4">Lista Componenti - <?= htmlspecialchars($_COOKIE['nomeProgetto']) ?></h1>
        <?php if (!isset($_SESSION['creation_phase'])): ?>
            <a class="btn ms-4" href="../item/item.php?nome=<?= urlencode($_COOKIE['nomeProgetto']) ?>" style="color: white;">Torna Indietro</a>
        <?php endif; ?>
    <?php endif; ?>

    <?php 
    if (isset($_SESSION['email'], $_COOKIE['creatore']) && $_SESSION['email'] == $_COOKIE['creatore']): ?>
        <div class="container m-4 p-0 w-50">
            <form action="componentiController.php" method="post">
                <div class="form-group mb-3">
                    <label class="form-label" for="nomeComponente">Nome Componente:</label>
                    <input class="form-control" type="text" id="nomeComponente" name="nomeComponente" maxlength="40" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="descrizione">Descrizione:</label>
                    <textarea class="form-control" id="descrizione" name="descrizione" maxlength="300" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="prezzo">Prezzo:</label>
                    <input class="form-control" type="number" id="prezzo" name="prezzo" step="0.01" min="0" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="quantita">Quantità:</label>
                    <input class="form-control" type="number" id="quantita" name="quantita" min="1" required>
                </div>
                <div class="form-group mb-3">
                    <button class="btn" type="submit" style="color: white;">Aggiungi Componente</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <table class="table-rounded text-center mx-auto w-75">
        <thead class="table-secondary">
            <tr class="w-100">
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

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo pb-3 pt-3">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>