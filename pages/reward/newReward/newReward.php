<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reward</title>
</head>
<body>
    <h1>Create a New Reward</h1>
    <form action="./newRewardController.php" method="POST" enctype="multipart/form-data">
        <label for="descrizione">Description:</label>
        <textarea id="descrizione" name="descrizione" required></textarea>
        <br>
        <label for="foto">Photo:</label>
        <input type="file" id="foto" name="foto" required>
        <br>

        <button type="submit">Create Reward</button>
    </form>
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['creation_phase']) && $_SESSION['creation_phase'] == 2) :
    ?>
        <a href='../../home/home.php'>Torna alla home</a>
    <?php elseif (!isset($_SESSION['creation_phase'])): ?>
    <a href='../reward.php'>Torna Indietro</a>
    <?php endif; ?>
</body>
</html>