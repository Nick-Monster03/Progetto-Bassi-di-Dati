<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reward</title>
    <style>
    body {
      font-family: 'Cinzel', serif;
      background: linear-gradient(135deg, #e0f7fa, #b3eafb);
      margin: 0;
      padding: 40px;
      text-align: center;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    h1 {
      color: #0a899a;
      font-size: 2.5rem;
      margin-bottom: 30px;
    }

    form {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: left;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
      color: #0a899a;
      font-size: 1.1rem;
    }

    textarea, input[type="file"] {
      width: 100%;
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      resize: vertical;
    }

    button {
      background-color: #0a899a;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #1aa9b2;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      color: #0a899a;
      font-weight: bold;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    @font-face {
      font-family: 'Cinzel';
      src: url('./font/Cinzel-Regular.ttf') format('truetype');
    }

    @media (max-width: 500px) {
      form {
        padding: 20px;
      }

      h1 {
        font-size: 2rem;
      }
    }
  </style>
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