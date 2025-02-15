<?php
    // try{
    //     $pdo=new PDO("mysql:host=localhost; dbname=prova_db", "root", "changeme");
    //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Imposta il modo di gestione degli errori
    //     echo "Connesso con successo!<br><br><br>";
    // }catch(PDOException){
    //     echo("errore di connessione al db <br>");
    //     exit();
    // }
    //     // Scrivi la query SQL
    // $query = "SELECT * FROM fiera";

    // // Esegui la query
    // $stmt = $pdo->prepare($query);
    // $stmt->execute();

    // // Recupera i risultati
    // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // // Visualizza i risultati
    // foreach ($results as $row) {
    //     echo "Nome: " . $row['nome'] . "<br>";
    //     echo "Ambito: " . $row['ambito'] . "<br>";
    //     echo "Ente: " . $row['ente'] . "<br><br>";
    // }
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["tipo"], $_GET["descrizione"], $_GET["importo"])){
      $tipo=$_GET["tipo"];
      $descrizione=$_GET["descrizione"];
      $importo=$_GET["importo"];
    
    try {
        $pdo=new PDO('mysql:host=localhost;dbname=prova_db','root', 'changeme');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      echo("[ERRORE] Connessione al DB '$dbName' non riuscita. Errore: ".$e->getMessage());
      exit();
    }
    
    
    try {
      $checkQuery = 'SELECT COUNT(*) FROM Movimenti WHERE Tipo = :tipo AND Descrizione = :descrizione AND Importo = :importo';
      $stmt = $pdo->prepare($checkQuery);
      $stmt->execute([':tipo' => $tipo, ':descrizione' => $descrizione, ':importo' => $importo]);
      $count = $stmt->fetchColumn();

      session_start();
      if ($count == 0) {
          // Se non esiste, esegui l'inserimento
          $sql = 'INSERT INTO Movimenti(Tipo, Descrizione, Importo) VALUES(:tipo, :descrizione, :importo)';
          $stmt = $pdo->prepare($sql);
          $stmt->execute([':tipo' => $tipo, ':descrizione' => $descrizione, ':importo' => $importo]);
          $res = $stmt->rowCount();
      } else {
          // Altrimenti, non eseguire l'inserimento
          $res = 0;
          echo "[ERRORE] Record già esistente.";
          header("Location: index.php");
          exit();
      }
      $_SESSION['res'] = $res;
    }
    catch(PDOException $e) {
      echo("[ERRORE] Query SQL (Insert) non riuscita. Errore: ".$e->getMessage());
      exit();
    }
    header("Location: pagina2.php");
    // Stampo risultato della query
    echo ("<b> Totale Righe Inserite nel DB: ".$res . "</b><br>");
    
}
?>