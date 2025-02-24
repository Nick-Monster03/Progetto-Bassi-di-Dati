<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="creatore.css">
</head>
  <?php
    try {
      $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "SELECT * FROM finanziamento";
      $stmt = $pdo->query($query);
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      echo "<table border='1'>";
      echo "<tr><th>Email Utente</th><th>Data Versamento</th><th>Nome Progetto</th><th>ID Reward</th><th>Importo</th></tr>";
      foreach ($results as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['emailUtente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dataVersamento']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nomeProgetto']) . "</td>";
        echo "<td>" . htmlspecialchars($row['idReward']) . "</td>";
        echo "<td>" . htmlspecialchars($row['importo']) . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } catch (PDOException $e) {

      echo "errore bischero";
    }
  ?>
<body>
</body>
</html>