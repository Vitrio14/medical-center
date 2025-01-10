<?php
session_start();

// Controllo se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php'); // Assicurati che il file di connessione al database esista

// Connessione al database
$conn = getDBConnection();

// Query per ottenere tutti i pazienti
$sql = "SELECT id, nome, cognome, data_nascita, telefono FROM pazienti";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Visualizza Pazienti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Elenco Pazienti Registrati</h2>

    <?php
    // Verifica se ci sono pazienti
    if ($result->num_rows > 0) {
        // Crea la tabella con i dati dei pazienti
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nome</th>';
        echo '<th>Cognome</th>';
        echo '<th>Data di Nascita</th>';
        echo '<th>Telefono</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Recupera i dati e mostra ogni paziente
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['nome'] . '</td>';
            echo '<td>' . $row['cognome'] . '</td>';
            echo '<td>' . $row['data_nascita'] . '</td>';
            echo '<td>' . $row['telefono'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        // Se non ci sono pazienti
        echo '<div class="alert alert-info" role="alert">Nessun paziente registrato.</div>';
    }

    // Chiudi la connessione
    $conn->close();
    ?>

    <a href="aggiungi_paziente.php" class="btn btn-primary mt-3">Aggiungi Nuovo Paziente</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
