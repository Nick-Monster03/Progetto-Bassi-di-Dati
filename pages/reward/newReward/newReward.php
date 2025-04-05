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

    <a href='../reward.php'>Torna indietro</a>

</body>
</html>