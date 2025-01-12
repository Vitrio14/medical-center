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
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Elimina Medico</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Eliminazione Medico</h2>

        <!-- Modale di conferma risultato -->
        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultModalLabel">
                            <?php echo isset($_SESSION['success_message']) ? 'Successo' : 'Errore'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo htmlspecialchars($_SESSION['success_message'] ?? $_SESSION['error_message']); ?>
                    </div>
                    <div class="modal-footer">
                        <a href="dashboard.php" class="btn btn-primary">Torna alla Dashboard</a>
                        <a href="visualizza_medici.php" class="btn btn-secondary">Visualizza Medici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
            resultModal.show();
        });
    </script>
</body>
</html>

