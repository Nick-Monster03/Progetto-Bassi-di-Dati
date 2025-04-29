<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inserisci Profili</title>
        <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./newProjectSoftware.css">
        <style>
            .top-right {
                position: absolute;
                top: 10px;
                right: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-3 w-50">
            <div class="header m-4">
                <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
                <?php
                require_once 'newProjectSoftwareController.php';
                echo '<h2 class="mt-2">Profili Skill per il progetto: ' . htmlspecialchars($nomeProgettoSoftware) . '</h2>';
            ?>
            </div>
            <?php 
                $profili = Init();
                if (!empty($profili)) {
                    echo '<table class="table-rounded text-center mx-auto w-75">';
                    echo '<thead class="table-secondary">
                                <tr>
                                    <th>Nome Profilo</th>
                                    <th>Nome Progetto Software</th>
                                    <th>Gestione Profilo</th>
                                </tr></thead>';
                    echo '<tbody>';
                    foreach ($profili as $profilo) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($profilo['nome']) . '</td>';
                        echo '<td>' . htmlspecialchars($profilo['nomeProgettoSoftware']) . '</td>';
                        echo '<td><a href="../../menageProfile/menageProfile.php?nome=' . urlencode($profilo['nome']) . '&nomeProgettoSoftware=' . urlencode($profilo['nomeProgettoSoftware']) . '" style="color: #055160;">+ Gestione Profilo</a></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p>Nessun profilo trovato.</p>';
                }
            ?>
            <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (!isset($_SESSION['creation_phase'])):
            ?>
                <div class="p-2">
                    <a href="../../home/home.php">
                        <button class="btn">Home</button>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="container mt-3 w-50">
            <div class="header m-4">
                <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
                <h2 class="mt-2">Inserisci un nuovo profilo</h2>
            </div>
            <form action="newProjectSoftwareController.php" method="post">
                <label for="profileName">Nome Profilo:</label>
                <input type="text" id="profileName" name="profileName" required><br><br>
                <button class="btn" type="submit">Invia</button>
            </form>
        </div>
        <?php
            //questi sono i due coockie che usa lo scripr manageProfile
            if (isset($_COOKIE['nomeProfilo'])) {
                setcookie('nomeProfilo', '', time() - 3600, '/');
            }

            if (isset($_COOKIE['nomeProgettoSoftware'])) {
                setcookie('nomeProgettoSoftware', '', time() - 3600, '/');
            }
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['creation_phase']) && $_SESSION['creation_phase'] == 1):
        ?>
            <div class="bottom-right" style="position: absolute; bottom: 10px; right: 10px;">
                <a class="btn" href="../../reward/newReward/newReward.php">Sezione reward</a>
            </div>
        <?php endif; ?>
    </body>
</html>