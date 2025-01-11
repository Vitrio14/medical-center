<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');
include('navbar.php');

// Controlla se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se è stato fornito l'ID dell'appuntamento
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Errore: ID appuntamento non specificato.");
}

$id = intval($_GET['id']);

// Connessione al database
$conn = getDBConnection();

// Recupera i dettagli dell'appuntamento
$sql = "SELECT * FROM appuntamenti WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$appuntamento = $result->fetch_assoc();

if (!$appuntamento) {
    die("Errore: Appuntamento non trovato.");
}

// Se il modulo è stato inviato, aggiorna l'appuntamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_appuntamento = $_POST['data_appuntamento'];
    $ora_appuntamento = $_POST['ora_appuntamento'];
    $note = $_POST['note'];
    $stato = $_POST['stato'];

    // Verifica che i campi obbligatori siano compilati
    if (empty($data_appuntamento) || empty($ora_appuntamento)) {
        echo "<div class='alert alert-danger'>Errore: Tutti i campi obbligatori devono essere compilati.</div>";
    } else {
        // Aggiorna i dati dell'appuntamento
        $sql_update = "UPDATE appuntamenti 
                       SET data_appuntamento = ?, ora_appuntamento = ?, note = ?, stato = ? 
                       WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('ssssi', $data_appuntamento, $ora_appuntamento, $note, $stato, $id);

        if ($stmt_update->execute() && $stmt_update->affected_rows > 0) {
            // Reindirizza con il parametro success=1
            header('Location: modifica_appuntamento.php?id=' . $id . '&success=1');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Errore nell'aggiornamento dell'appuntamento.</div>";
        }
    }
}

// Chiudi la connessione al database
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Modifica Appuntamento</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Modifica Appuntamento</h2>
        <form method="POST" action="modifica_appuntamento.php?id=<?php echo htmlspecialchars($id); ?>">
            <!-- Data Appuntamento -->
            <div class="mb-3">
                <label for="data_appuntamento" class="form-label">Data Appuntamento</label>
                <input type="date" class="form-control" id="data_appuntamento" name="data_appuntamento" 
                       value="<?php echo htmlspecialchars($appuntamento['data_appuntamento']); ?>" required>
            </div>

            <!-- Ora Appuntamento -->
            <div class="mb-3">
                <label for="ora_appuntamento" class="form-label">Ora Appuntamento</label>
                <input type="time" class="form-control" id="ora_appuntamento" name="ora_appuntamento" 
                       value="<?php echo htmlspecialchars($appuntamento['ora_appuntamento']); ?>" required>
            </div>

            <!-- Note -->
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea class="form-control" id="note" name="note" rows="3"><?php echo htmlspecialchars($appuntamento['note']); ?></textarea>
            </div>

            <!-- Stato -->
            <div class="mb-3">
                <label for="stato" class="form-label">Stato</label>
                <select class="form-select" id="stato" name="stato">
                    <option value="Non confermato" <?php echo ($appuntamento['stato'] === 'Non confermato') ? 'selected' : ''; ?>>Non confermato</option>
                    <option value="Confermato" <?php echo ($appuntamento['stato'] === 'Confermato') ? 'selected' : ''; ?>>Confermato</option>
                    <option value="Annullato" <?php echo ($appuntamento['stato'] === 'Annullato') ? 'selected' : ''; ?>>Annullato</option>
                </select>
            </div>

            <!-- Pulsante per salvare -->
            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
            <a href="gestione_appuntamenti.php" class="btn btn-secondary">Annulla</a>
        </form>
    </div>

    <!-- Modal per notifica -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Appuntamento Aggiornato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    L'appuntamento è stato aggiornato con successo! Cosa desideri fare?
                </div>
                <div class="modal-footer">
                    <a href="dashboard.php" class="btn btn-primary">Vai alla Dashboard</a>
                    <a href="gestione_appuntamenti.php" class="btn btn-secondary">Visualizza Appuntamenti</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var successModalElement = document.getElementById('successModal');
            if (successModalElement) {
                var successModal = new bootstrap.Modal(successModalElement);
                successModal.show();
            }
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
