<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bostarter</title>
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
    <script src="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</head>
<?php
    include('homeController.php');
    //nel caso in cui l' utente decida di tornare indietro dopo essersi loggato come amministratore
    //ma senza aver inserito il codice di sicurezza il coockie status viene eliminato.
    //nel caso in cui l' utente decidesse di tornare indietro senza aver completato la registrazione
    //come amministratore il ccockie is_administrator viene eliiinato.
    //nel caso in cui l' utentee creatore abbia terminato il processo di creazione di un progetto:
    //$_SESSION['creation_phase']=0 dobbiamo ancora creare il rrpogetto
    //$_SESSION['creation_phase']=1 stiamo scegliendo il profilo da inserire 
    //$_SESSION['creation_phase']=2 stiamo scegliendo almeno un reward
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['status'])) {
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['is_administrator'])) {
        unset($_SESSION['is_administrator']);
    }
    if (isset($_SESSION['creation_phase'])) {
        unset($_SESSION['creation_phase']);
    }
 ?>
<body class="bg-light">
    <header id="main-header">
        <nav class="navbar navbar-expand-lg navbar-light sticky-top p-4">
            <div class="container-fluid flex-column">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <a class="navbar-brand" href="/pages/home/home.php"><img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo"></a>

                    <div class="d-flex m-3 gap-3">
                        <?php if (!isset($_SESSION['user_role']) ): ?>
                            <div>
                                <a class="btn btn-primary" href="/pages/register/register.php">SignIn</a>
                                <a class="btn btn-primary" href="/pages/login/login.php">LogIn</a>
                            </div>
                        <?php else: ?>
                            <a class="btn btn-primary text-nowrap h-100" href="../profiloUtente/profiloUtente.php">Visualizza Profilo</a>
                        <?php if ($_SESSION['user_role'] == 'creatore'): ?>
                            <a class="btn btn-primary text-nowrap h-100" href="../newProject/newProject.php">Crea Progetto</a>
                            <a class="btn btn-primary text-nowrap h-100" href="../candidature/candidatura.php">Visualizza Candidature</a>
                        <?php elseif ($_SESSION['user_role'] == 'amministratore'): ?>
                            <a class="btn btn-primary text-nowrap h-100" href="../competenze/competenze.php">Visualizza Lista Competenze</a>
                        <?php endif; ?>
                            <a class="btn btn-primary text-nowrap h-100" href="../skillList/skillList.php">Mie Competenze</a>
                            <button class="btn btn-primary rounded" onclick="window.location.href='homeController.php?action=logout'">Logout</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="w-100">       
                    <div class="d-flex justify-content-center">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="#top3vicinoscadenza" class="menu-item">Progetti in Chiusura</a></li>
                            <li class="nav-item"><a class="nav-link" href="#top3creatori"class="menu-item">Classifica creatori</a></li>
                            <li class="nav-item"><a class="nav-link" href="#top3finanziatori" class="menu-item">Classifica Finanziatori</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid p-4 bg-light">
        <h1 class="titoli text-center pt-4">I NOSTRI PROGETTI</h1>
        <div class="row justify-content-center">
            <?php
            if (!empty($GLOBALS['projects'])) {
                $projects = $GLOBALS['projects'];
                foreach ($projects as $i => $project) {
                    $project_name = htmlspecialchars($project['nome']);
                    $project_link = isset($_SESSION['user_role']) ? "../item/item.php?nome=" . urlencode($project_name) : "#";

            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 g-5">
                <div class="card text-center pb-4 align-items-center pt-5" onclick="window.location.href='<?php echo $project_link; ?>'">
                    <img class="card-img-top rounded-circle w-25 pb-2" src="/services/uploads/cybersecurity_audit.jpeg" alt="">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $project_name; ?></h3>
                        <p class="card-text">Descrizione breve del progetto.</p>
                        <a class="btn btn-primary" href="#">Scopri di più</a>
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
    <div class="container-fluid mt-5">
        <h1 class="titoli mb-4 text-center">CLASSIFICHE BOSTARTER</h1>
        <div class="container bg-white w-75 border border-3 rounded-3 shadow-sm p-4">
            <!-- Top 3 Creatori -->
            <h2 class="titoli pl-3 pt-4" id='top3creatori'>Creatori</h2>
            <p class="subtitle mb-4">Di seguito la top 3 dei creatori più affidabili tra i nostri membri!</p>
            <hr class="custom-line">
            <table class="table-rounded text-center mx-auto w-75">
                <thead class="table-secondary">
                    <tr>
                        <th>Email</th>
                        <th>Nickname</th>
                        <th>Affidabilità</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Top3Creatori as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['nickname']) ?></td>
                        <td><?= htmlspecialchars($row['affidabilità']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Top 3 Progetti Vicino alla Scadenza -->
            <h2 class="titoli pl-3 pt-4 mt-5" id='top3vicinoscadenza'>Progetti Vicino alla Scadenza</h2>
            <p class="subtitle mb-4">Di seguito i progetti a scadenza più recente!</p>
            <hr class="custom-line">
            <table class="table-rounded text-center mx-auto w-75">
                <thead class="table-secondary">
                    <tr>
                        <th>Nome Progetto</th>
                        <th>Rimanenza (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Top3ProgettiVicinoScadenza as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars(number_format($row['rimanenza'], 2, ',', '.')) ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

           <!-- Top 3 Finanziatori -->
            <h2 class="titoli pl-3 pt-4 mt-5" id='top3finanziatori'>Finanziatori</h2>
            <p class="subtitle mb-4">Di seguito la top 3 con i migliori finanziatori sulla pittaforma!</p>
            <hr class="custom-line">
            <table class="table-rounded text-center mx-auto w-75">
                <thead class="table-secondary">
                    <tr>
                        <th>Nickname</th>
                        <th>Totale Finanziato (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top3finanziatori as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nickname']) ?></td>
                            <td><?= htmlspecialchars(number_format($row['totale'], 2, ',', '.')) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
   </div>


    <footer class="py-4 mt-5">
        <div class="container text-center">
            <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo pb-3 pt-3">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>