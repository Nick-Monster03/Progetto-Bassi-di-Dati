<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bostarter</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<?php
    include('homeController.php');
?>
<body>
    <header>
        <div class="container d-flex flex-column align-items-center py-3">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h1 class="titleWebSite">Bostarter</h1>
                <div class="search-bar d-flex">
                   <form action="/pages/home/searchResults.php" method="GET" class="d-flex">
                       <input type="text" name="query" id="search-input" placeholder="Cerca progetti..." class="form-control" onkeyup="searchProjects()">
                       <button type="submit" class="btn btn-primary">Cerca</button>
                       <div id="search-dropdown" class="search-dropdown">
                           <!-- Risultati della ricerca -->
                       </div>
                   </form>
                </div>
                <div id='actions'>
                    <?php if (!isset($_SESSION['user_role'])): ?>
                        <a href="/pages/register/register.php">Registrati</a>
                        <a href="/pages/login/login.php">Login</a>
                    <?php else: ?>
                        <a href="#">Visualizza Profilo</a>
                        <?php if ($_SESSION['user_role'] == 'creatore'): ?>
                            <a href="../newProject/newProject.php">Crea Progetto</a>
                            <a href="../candidature/candidatura.php">Visualizza Candidature</a>
                        <?php elseif ($_SESSION['user_role'] == 'amministratore'): ?>
                            <a href="../skillList/skillList.php">Visualizza Lista Competenze</a>
                        <?php endif; ?>
                        <a href="../skillList/skillList.php">Mie Competenze</a>
                        <button onclick="window.location.href='homeController.php?action=logout'">Logout</button>
                    <?php endif; ?>
                </div>
            </div>
            <nav class="mt-2">
                <a href="#" class="menu-item">Tecnologia</a>
                <a href="#" class="menu-item">Design</a>
                <a href="#" class="menu-item">Arte</a>
                <a href="#" class="menu-item">Musica</a>
            </nav>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="row">
                    <?php
                    if (!empty($GLOBALS['projects'])) {
                        $projects = $GLOBALS['projects'];
                        foreach ($projects as $i => $project) {
                            $project_name = htmlspecialchars($project['nome']);
                            $project_link = isset($_SESSION['user_role']) ? "../item/item.php?nome=" . urlencode($project_name) : "#";
                            $card_colors = ['#f8f9fa', '#e9ecef', '#dee2e6', '#ced4da', '#adb5bd'];
                    ?>
                    <div class="col-12 col-md-6 mb-4">
                        <div class="card" style="background-color: <?php echo $card_colors[$i % count($card_colors)]; ?>; cursor: pointer; transition: transform 0.3s;" onclick="window.location.href='<?php echo $project_link; ?>'">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $project_name; ?></h5>
                                <p class="card-text">Descrizione breve del progetto. Clicca per maggiori dettagli.</p>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo "<p class='text-center'>Nessun progetto trovato.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>

    <script>
        function searchProjects() {
            const query = document.getElementById('search-input').value;
            if (query.length < 3) {
                document.getElementById('search-dropdown').innerHTML = '';
                document.getElementById('search-dropdown').classList.remove('show');
                return;
            }
            fetch(`homeController.php?action=search&query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let results = '';
                    data.forEach(item => {
                        results += `<a href="../item/item.php?nome=${encodeURIComponent(item.nome)}" class="dropdown-item">${item.nome}</a>`;
                    });
                    document.getElementById('search-dropdown').innerHTML = results;
                    document.getElementById('search-dropdown').classList.add('show');
                });
        }

        function submitSearch() {
            const query = document.getElementById('search-input').value;
            if (query.length >= 3) {
                window.location.href = `/pages/home/searchResults.php?query=${query}`;
            }
        }
    </script>
</body>
</html>