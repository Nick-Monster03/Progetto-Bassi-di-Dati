<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project</title>
    <link rel="stylesheet" href="../../home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <header>
        <div class="container d-flex flex-column align-items-center py-3">
            <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo">
        </div>
    </header>

    <div class="container mt-5">
        <h1 class="text-center">Crea un nuovo progetto</h1>
        <form action="newProjectController.php" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="projectName" class="form-label">Nome Progetto:</label>
                <input type="text" id="projectName" name="projectName" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrizione:</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="budget" class="form-label">Budget:</label>
                <input type="number" id="budget" name="budget" step="0.1" class="form-control" placeholder="Enter budget" required>
            </div>

            <div class="mb-3">
                <label for="endDate" class="form-label">Data limite:</label>
                <input type="date" id="endDate" name="endDate" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipologia:</label>
                <div>
                    <label class="form-check-label me-3">
                        <input type="radio" id="tipologia" name="tipologia" value="Hardware" class="form-check-input" required> Progetto Hardware
                    </label>
                    <label class="form-check-label">
                        <input type="radio" id="tipologia" name="tipologia" value="Software" class="form-check-input" required> Progetto Software
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="immagine" class="form-label">Seleziona immagine:</label>
                <input type="file" name="immagine" id="immagine" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Project</button>
        </form>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>