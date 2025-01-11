<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');

// Controlla se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Controlla se è stato inviato un ID medico
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $medico_id = intval($_POST['id']); // Converti in un numero intero per sicurezza

    // Connessione al database
    $conn = getDBConnection();

    if (!$conn) {
        $_SESSION['error_message'] = "Errore di connessione al database.";
        header('Location: visualizza_medici.php');
        exit();
    }

    // Elimina il medico dal database
    $sql = "DELETE FROM medici WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $medico_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Medico eliminato con successo.";
            } else {
                $_SESSION['error_message'] = "Medico non trovato o già eliminato.";
            }
        } else {
            $_SESSION['error_message'] = "Errore durante l'eliminazione del medico: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Errore nella query: " . $conn->error;
    }

    // Chiudi la connessione
    $conn->close();

    // Redirigi alla pagina di visualizzazione
    header('Location: visualizza_medici.php');
    exit();
} else {
    $_SESSION['error_message'] = "Richiesta non valida.";
    header('Location: visualizza_medici.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Visualizza Medici</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Elenco Medici</h2>

        <!-- Messaggi di successo o errore -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Specializzazione</th>
                    <th>Data Creazione</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['cognome']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['specializzazione']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_creazione']); ?></td>
                            <td>
                                <!-- Pulsante per modificare -->
                                <a href="modifica_medico.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifica</a>
                                
                                <!-- Pulsante per eliminare -->
                                <form action="elimina_medico.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo medico?');">Elimina</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Nessun medico trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
