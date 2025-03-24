<!DOCTYPE html>
<html>
<head>
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
        <div class="titleWebSite">
            Bostarter
        </div>
        <?php if (!isset($_SESSION['user_role'])): ?>
        <div id='actions'>
                <a href="/pages/register/register.php" >Registrati</a> |
                <a href="/pages/login/login.php" >Login</a>
        </div>
        <?php else: 
            echo "<div id='actions'><a href='#'>Visualizza Profilo</a>";
            if ($_SESSION['user_role'] == 'creatore') {
            echo " | <a href='../newProject/newProject.php'>Crea Progetto</a>";
            echo " | <a href='#'>Visualizza Candidature</a>";
            } elseif ($_SESSION['user_role'] == 'amministratore') {
            echo "| <a href='#'>Visualizza Lista Competenze</a>";
            }
            echo " | <button onclick=\"window.location.href='homeController.php?action=logout'\">Logout</button></div>";
        ?>
        <?php endif; ?>
    </header>
    <div class="container" style="width: 100vw; height: 95vh;">
        <div class="row" style="height: 100%; width:100%;">   
            <div class="col-4" style="height: 100%; background-color: #f8f9fa;">
                <?php
                    $projects = $GLOBALS['projects'];
                    if (isset($_SESSION['user_role'])) {
                        echo "<h1><a href='../item/item.php?nome=" . urlencode($projects['nome'][0]) . "'>" . htmlspecialchars($projects['nome'][0]) . "</a></h1>";
                    } else {
                        echo("<h1>" . htmlspecialchars($projects['nome'][0]) . "</h1>");
                    }
                ?>
            </div>
            <div class="col-8" style="height: 100%;">
                <div class="row" style="height: 50%;">
                    <div class="col-6" style="height: 100%; background-color: #e9ecef;">
                        <?php
                             $projects = $GLOBALS['projects'];
                             if (isset($_SESSION['user_role'])) {
                                echo "<h1><a href='../item/item.php?nome=" . urlencode($projects['nome'][1]) . "'>" . htmlspecialchars($projects['nome'][1]) . "</a></h1>";
                            } else {
                                echo("<h1>" . htmlspecialchars($projects['nome'][1]) . "</h1>");
                            }
                        ?>
                    </div>
                    <div class="col-6" style="height: 100%; background-color: #dee2e6;">
                        <?php
                             $projects = $GLOBALS['projects'];
                             if (isset($_SESSION['user_role'])) {
                                echo "<h1><a href='../item/item.php?nome=" . urlencode($projects['nome'][2]) . "'>" . htmlspecialchars($projects['nome'][2]) . "</a></h1>";
                            } else {
                                echo("<h1>" . htmlspecialchars($projects['nome'][2]) . "</h1>");
                            }
                        ?>
                    </div>
                </div>
                <div class="row" style="height: 50%;">
                    <div class="col-6" style="height: 100%; background-color: #ced4da;">
                        <?php
                             $projects = $GLOBALS['projects'];
                             if (isset($_SESSION['user_role'])) {
                                echo "<h1><a href='../item/item.php?nome=" . urlencode($projects['nome'][3]) . "'>" . htmlspecialchars($projects['nome'][3]) . "</a></h1>";
                            } else {
                                echo("<h1>" . htmlspecialchars($projects['nome'][3]) . "</h1>");
                            }
                        ?>
                    </div>
                    <div class="col-6" style="height: 100%; background-color: #adb5bd;"> 
                        <?php
                             $projects = $GLOBALS['projects'];
                             if (isset($_SESSION['user_role'])) {
                                echo "<h1><a href='../item/item.php?nome=" . urlencode($projects['nome'][4]) . "'>" . htmlspecialchars($projects['nome'][4]) . "</a></h1>";
                            } else {
                                echo("<h1>" . htmlspecialchars($projects['nome'][4]) . "</h1>");
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>