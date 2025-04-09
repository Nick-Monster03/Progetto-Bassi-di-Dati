<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        session_start();
        include 'profiloUtenteController.php';
    ?>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Profilo Utente</h3>
            </div>
            <div class="card-body">
                <?php if (isset($result) && count($result) > 0): ?>
                    <?php $utente = $result[0]; ?>
                    <p><strong>Email:</strong> <?= htmlspecialchars($utente['email']) ?></p>
                    <p><strong>Nickname:</strong> <?= htmlspecialchars($utente['nickname']) ?></p>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($utente['nome']) ?></p>
                    <p><strong>Cognome:</strong> <?= htmlspecialchars($utente['cognome']) ?></p>
                    <p><strong>Anno di Nascita:</strong> <?= htmlspecialchars($utente['annoNascita']) ?></p>
                    <p><strong>Luogo di Nascita:</strong> <?= htmlspecialchars($utente['luogoNascita']) ?></p>
                    <p><strong>Ruolo:</strong> <?= htmlspecialchars($_SESSION['user_role']) ?></p>
                    <?php
                        if ($_SESSION['user_role'] == 'creatore') {
                            echo '<p><strong>Progetti Creati:</strong></p>';
                            foreach ($progettiCreati as $progetto) {
                                echo '<p>' . htmlspecialchars($progetto['nome']) . '</p>';
                            }
                        } 
                        echo '<p><strong>Progetti Finanziati:</strong></p>';
                        foreach ($progettiFinanziati as $progetto) {
                            echo '<p>' . htmlspecialchars($progetto['nomeProgetto']) . ': ' . htmlspecialchars($progetto['totale']) .'</p>';
                        }
                        
                    ?>
                <?php else: ?>
                    <div class="alert alert-warning">Nessun dato trovato per questo utente.</div>
                <?php endif; ?>

                <a href="javascript:history.back()" class="btn btn-secondary mt-3">&larr; Torna indietro</a>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>
