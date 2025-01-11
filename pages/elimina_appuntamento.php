<?php
// Include file di connessione al database
include_once('../includes/db_connect.php');

// Variabile per messaggi (da mostrare nel modal)
$message = "";
$success = false;

// Controlla se l'ID è stato passato tramite GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Converte l'ID in un numero intero per sicurezza

    // Connessione al database
    $conn = getDBConnection();

    // Query per eliminare l'appuntamento
    $sql = "DELETE FROM appuntamenti WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // Verifica se l'appuntamento è stato eliminato
        if ($stmt->affected_rows > 0) {
            $message = "Appuntamento eliminato con successo!";
            $success = true;
        } else {
            $message = "Errore: ID appuntamento non trovato!";
        }
    } else {
        $message = "Errore nella preparazione della query: " . htmlspecialchars($conn->error);
    }
} else {
    $message = "Errore: ID appuntamento non fornito!";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Elimina Appuntamento</title>
</head>
<body>
    <div class="container mt-5">
        <!-- Modal -->
        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultModalLabel"><?php echo $success ? "Operazione Riuscita" : "Errore"; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <div class="modal-footer">
                    <a href="gestione_appuntamenti.php" class="btn btn-primary">Gestione appuntamenti</a>
                    <a href="dashboard.php" class="btn btn-secondary">Torna alla Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script per aprire il modal automaticamente -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
            resultModal.show();
        });
    </script>
</body>
</html>
