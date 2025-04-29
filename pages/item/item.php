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

    <style>
        @font-face {
            font-family: 'Cinzel';
            src: url('./font/Cinzel-Regular.ttf') format('truetype');
            font-weight: light;
            font-style: light;
        }
        .titoli {
            font-size: 3rem;
            line-height: 1.1;
            font-family: 'Cinzel', serif;
            font-weight: light;
        }

        .btn {
            background-color: #0a899a !important;
            border: none !important;
        }
        .btn:hover {
            background-color: #1aa9b2 !important;
        }

        /* Footer */
        footer {
            width: 100%;
            background: linear-gradient(135deg, #e0f7fa, #b3eafb);
            color: #0a899a;
            padding: 20px 0;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <div class="container d-flex flex-column align-items-center py-3">
        <a class="navbar-brand text-center" href="/pages/home/home.php"><img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo w-50"></a>
        </div>
    </header>
    
    <div class="container">
        <?php
            session_start();
            
            include './itemController.php';
        ?>

        <?php if ($result): ?>
            <div class="project-container d-flex flex-column align-items-center">
                <div class="w-75 p-0 bg-white border rounded shadow-sm">
                    <div class="p-3 rounded-top text-center" style="background-color: #0a899a;">
                        <h2 class="mb-0" style="color: white;">Metadati</h2>
                    </div>
                    
                    <div class="project-photos text-center my-2">
                        <?php
                            if ($rows) {
                                //$rows rappresenta le foto ed essendo che un progetto può avere più foto ciclo 
                                //$rows con un for each
                                foreach ($rows as $row) {
                                    if (isset($row['foto'])) {
                                        $blob = $row['foto'];
                                        $base64Image = base64_encode($blob);
                                        echo "<img src='data:image/jpeg;base64," . htmlspecialchars($base64Image, ENT_QUOTES, 'UTF-8') . "' alt='Foto Progetto' style='max-width: 400px; margin: 10px;'>";
                                    } else {
                                        echo "<p>Nessuna foto disponibile.</p>";
                                    }
                                }
                            } else {
                                echo "<p>Nessuna foto disponibile.</p>";
                            }
                        ?>
                    </div>
                    <ul class="list-group list-group-flush">
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
                            <li class="list-group-item"><strong><span class="label" style="color: #0a899a;"><?= htmlspecialchars($column) ?>:</span></strong>&nbsp;<?= htmlspecialchars($value) ?></li>
                        <?php endforeach; ?>
                        <div class="budget-bar p-3">
                            <p><strong style="color: #0a899a;">Budget attuale:</strong> €<?= number_format($valoreAttuale, 2, ',', '.') ?> / €<?= number_format($budget, 2, ',', '.') ?></p>
                            <?php
                                $percentuale = min(100, ($valoreAttuale / $budget) * 100);
                            ?>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $percentuale ?>%;" aria-valuenow="<?= $percentuale ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= $percentuale == 100 ? '100%' : number_format($percentuale, 2, ',', '.') . '%' ?>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>

                <div class="project-actions text-center mt-4">
                    <?php if ($stato == "aperto"): ?>
                        <a href='../finanziamento/finanziamento.php' class="btn btn-success mt-2">Finanzia progetto</a>
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

                    <a href="../commenti/commenti.php" class="btn btn-primary mt-2">Visualizza Commenti</a>

                    <?php if ($stato == "aperto"): ?>
                        <a href="../reward/reward.php" class="btn btn-success mt-2">Visualizza Reward</a>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-secondary" onclick="window.location.href='../home/home.php'">Torna alla Home</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo pb-3 pt-3 w-25">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
    
<!--     PER DEBUGGING
    <?php ini_set('display_errors', 1);
error_reporting(E_ALL);?> -->
</body>
</html>