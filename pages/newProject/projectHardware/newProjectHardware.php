<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Form</title>
</head>
<body>
    <div style="position: absolute; top: 10px; right: 10px;">
        <a href="../../home/home.php" style="text-decoration: none; padding: 10px 15px; background-color: #007BFF; color: white; border-radius: 5px;">Home</a>
    </div>
    <h1>HARDWARE</h1>
    <form action="newProjectHardwareController.php" method="post">
        <label for="hardware">Seleziona Componenti:</label>
        <select id="hardware" name="hardware"  required>
        <?php
           require 'newProjectHardwareController.php';
           $list = getList();
           foreach ($list as $item) {
            echo "<option value=\"{$item['nome']}\">" . ucfirst($item['nome']) . "</option>";
            }
        ?>
        </select>
        <br><br>
        <label for="quantity">Quantità:</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
        <br><br>
        <button type="submit">Invia</button>
        
    </form>
    <div class="bottom-right" style="position: absolute; bottom: 10px; right: 10px;">
        <a href="../../reward/newReward/newReward.php">Sezione reward</a>
    </div>
    
    <?php
    // ini_set('display_errors', 1);
    echo("<h2>Componenti inseriti:</h2>");
    $components = getComponentsIn();
    echo "<ul>";
    foreach ($components as $component) {
        echo "<li>" . htmlspecialchars($component['nomeComponente']) . " - Quantità: " . htmlspecialchars($component['quantita']) . "</li>";
    }
    echo "</ul>";
    ?>

</body>
</html>