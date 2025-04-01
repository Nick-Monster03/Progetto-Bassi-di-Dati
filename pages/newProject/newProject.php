<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project</title>
</head>
<body>
    <h1>Create a New Project</h1>
    <form action="newProjectController.php" method="post">
        <label for="projectName">Nome Progetto:</label>
        <input type="text" id="projectName" name="projectName" required>
        <br><br>
        
        <label for="description">Descrizione:</label>
        <textarea id="description" name="description" required></textarea>
        <br><br>
        
        <label for="teamMembers">Budget:</label>
        <input type="number" id="budget" name="budget" step="0.1" placeholder="Enter budget" required>
        <br><br>

        <label for="endDate">Data limite:</label>
        <input type="date" id="endDate" name="endDate" required>
        <br><br>

        <label for="endDate">Tipologia:</label>
        <label>
            <input type="radio" id="tipologia" name="tipologia" value="Hardware" required> Progetto Hardware
        </label>
        <label>
            <input type="radio" id="tipologia" name="tipologia" value="Software" required> Progetto Software
        </label>
        <br><br>
        <label>Seleziona immagine:</label>
        
            <input type="file" name="immagine" id="immagine" required>
        <br><br>
        
        <button type="submit">Add Project</button>
    </form>
</body>
</html>