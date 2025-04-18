<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Progetto</title>

    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="../styles/project.css"> -->
</head>
<body>
    <header>
        <div class="container d-flex flex-column align-items-center py-3">
            <h1 class="titleWebSite">Bostarter</h1>
        </div>
    </header>
    
    <div class="container mt-4">
        <?php
            session_start();
            include './itemController.php';
            echo "<script>console.log('Email: " . ($_SESSION["email"] ?? 'non c è nessuan mail') . "');</script>";
        ?>

        <?php if ($result): ?>
            <div class="project-container">
                <div class="project-info">
                        <?php foreach ($result as $column => $value): ?>
                            <?php
                                if ($column == "emailUtenteCreatore") {
                                    $creatore = $value;
                                } elseif ($column == "stato") {
                                    $stato = $value;
                                } elseif ($column == "budget") {
                                    $budget = $value;
                                }
                            ?>
                            <p><strong><span class="label"><?= htmlspecialchars($column) ?>:</span></strong>&nbsp;<?= htmlspecialchars($value) ?></p>
                    <?php endforeach; ?>
                </div>

                

                <div class="budget-bar">
                    <p>Budget attuale: <strong>€<?= number_format($valoreAttuale, 2, ',', '.') ?></strong> / €<?= number_format($budget, 2, ',', '.') ?></p>
                    <?php
                        $percentuale = min(100, ($valoreAttuale / $budget) * 100);
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $percentuale ?>%;" aria-valuenow="<?= $percentuale ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $percentuale == 100 ? '100%' : number_format($percentuale, 2, ',', '.') . '%' ?>
                        </div>
                    </div>
                </div>

                <div class="project-actions text-center mt-4">
                    <?php if ($stato == "aperto"): ?>
                        <a href='../finanziamento/finanziamento.php' class="btn btn-success">Finanzia progetto</a>
                    <?php else: ?>
                        <h5 class="text-danger">Progetto chiuso</h5>
                        <p><span class="text-muted">Il progetto risulta chiuso, quindi non puoi effettuare finanziamenti.</span></p>
                    <?php endif; ?>
                    
                    <?php 
                     if ($stato == "aperto" && $isSoftware): ?>
                        <?php if ($creatore != $_SESSION["email"]): ?>
                            <a href="../richiestaCandidatura/richiestaCandidatura.php" class="btn btn-primary mt-2">Lavora per questo progetto</a>
                        <?php else: ?>
                            <a href="../newProject/projectSoftware/newProjectSoftware.php" class="btn btn-warning mt-2">Aggiungi Profilo</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!$isSoftware): ?>
                        <a href="../componenti/componenti.php" class="btn btn-primary mt-2">Visualizza Componenti</a>
                    <?php endif; ?>

                    <a href="../commenti/commenti.php" class="btn btn-outline-primary mt-2">Visualizza Commenti</a>

                    <?php if ($stato == "aperto"): ?>
                        <a href="../reward/reward.php" class="btn btn-outline-success mt-2">Visualizza Reward</a>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-secondary" onclick="window.location.href='../home/home.php'">Torna alla Home</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
    <?php ini_set('display_errors', 1);
error_reporting(E_ALL);?>
</body>
</html>