<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="home.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<?php
    session_start();
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $_SESSION['pdo'] = $pdo;
    } catch (PDOException $e) {
        echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
        exit();
    }

    $query = $pdo->query('SELECT * FROM Progetto');
    $result = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $column => $value) {
            $result[$column][] = $value;
        }
    }
    $_SESSION['result'] = $result;
     // For debugging purposes
    // echo "<table border='1'>";
    // echo "<tr><th>Nome</th><th>Descrizione</th><th>Data Inserimento</th><th>Budget</th><th>Data Limite</th><th>Stato</th><th>Email Utente Creatore</th></tr>";
    // while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    //     echo "<tr>";
    //     echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
    //     echo "<td>" . htmlspecialchars($row['descrizione']) . "</td>";
    //     echo "<td>" . htmlspecialchars($row['data_inserimento']) . "</td>";
    //     echo "<td>" . htmlspecialchars($row['budget']) . "</td>";
    //     echo "<td>" . htmlspecialchars($row['data_limite']) . "</td>";
    //     echo "<td>" . htmlspecialchars($row['stato']) . "</td>";
    //     echo "<td>" . htmlspecialchars($row['emailUtenteCreatore']) . "</td>";
    //     echo "</tr>";
    // }
    // echo "</table>";
    // ?>

<body>
    <header>
        <div class="titleWebSite">
            Bostarter
        </div>
        <div id='actions'>
            <a href="register.php" >Registrati</a> | 
            <a href="login_creator.php" >Login Creatore</a> | 
            <a href="login_admin.php" >Login Amministratore</a>
        </div>
    </header>
    <div class="container" style="width: 100vw; height: 95vh;">
        <div class="row" style="height: 100%; width:100%;">   
            <div class="col-4" style="height: 100%; background-color: #f8f9fa;">
                <?php
                    $result = $_SESSION['result'];
                    echo("<h1>" . htmlspecialchars($result['nome'][0]) . "</h1>");
                ?>
            </div>
            <div class="col-8" style="height: 100%;">
                <div class="row" style="height: 50%;">
                    <div class="col-6" style="height: 100%; background-color: #e9ecef;">
                        <?php
                             $result = $_SESSION['result'];
                             echo("<h1>" . htmlspecialchars($result['nome'][1]) . "</h1>");
                        ?>
                    </div>
                    <div class="col-6" style="height: 100%; background-color: #dee2e6;">
                        <?php
                             $result = $_SESSION['result'];
                             echo("<h1>" . htmlspecialchars($result['nome'][2]) . "</h1>");
                        ?>
                    </div>
                </div>
                <div class="row" style="height: 50%;">
                    <div class="col-6" style="height: 100%; background-color: #ced4da;">
                        <?php
                             $result = $_SESSION['result'];
                             echo("<h1>" . htmlspecialchars($result['nome'][3]) . "</h1>");
                        ?>
                    </div>
                    <div class="col-6" style="height: 100%; background-color: #adb5bd;"> 
                        <?php
                             $result = $_SESSION['result'];
                             echo("<h1>" . htmlspecialchars($result['nome'][4]) . "</h1>");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>