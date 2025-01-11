<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');
include('navbar.php');

// Controlla se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connessione al database
$conn = getDBConnection();

// Recupera tutti gli appuntamenti
$sql = "SELECT a.id, 
               a.data_appuntamento, 
               a.ora_appuntamento, 
               a.note, 
               a.stato, 
               CONCAT(p.nome, ' ', p.cognome) AS paziente, 
               CONCAT(m.nome, ' ', m.cognome) AS medico 
        FROM appuntamenti a
        JOIN pazienti p ON a.paziente_id = p.id
        JOIN medici m ON a.medico_id = m.id";
$result = $conn->query($sql);

// Chiudi la connessione al database
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Gestione Appuntamenti</title>
</head>
<body>

    <!-- Contenuto Principale -->
    <div class="container mt-5">
        <h2>Gestione Appuntamenti</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paziente</th>
                    <th>Medico</th>
                    <th>Data</th>
                    <th>Ora</th>
                    <th>Note</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['paziente']); ?></td>
                            <td><?php echo htmlspecialchars($row['medico']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_appuntamento']); ?></td>
                            <td><?php echo htmlspecialchars($row['ora_appuntamento']); ?></td>
                            <td><?php echo htmlspecialchars($row['note']); ?></td>
                            <td><?php echo htmlspecialchars($row['stato']); ?></td>
                            <td>
                                <!-- Pulsante per modificare -->
                                <a href="modifica_appuntamento.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifica</a>
                                
                                <!-- Pulsante per eliminare -->
                                <a href="elimina_appuntamento.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Sei sicuro di voler eliminare questo appuntamento?');">Elimina</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Nessun appuntamento trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="aggiungi_appuntamento.php" class="btn btn-success">Aggiungi Nuovo Appuntamento</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
