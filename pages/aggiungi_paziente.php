<?php
session_start();

// Controllo se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php'); // Connessione al database
include('navbar.php');

$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data_nascita = $_POST['data_nascita'];
    $indirizzo = $_POST['indirizzo'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $note = $_POST['note'];

    $file_allegato = null;
    if (!empty($_FILES['file']['name'])) {
        $upload_dir = '../uploads/';
        $file_name = basename($_FILES['file']['name']);
        $target_file = $upload_dir . $file_name;

        // Salva il file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_allegato = $file_name;
        }
    }

    $conn = getDBConnection();

    // Query per aggiungere un nuovo paziente
    $sql = "INSERT INTO pazienti (nome, cognome, data_nascita, indirizzo, telefono, email, note, file_allegato) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $nome, $cognome, $data_nascita, $indirizzo, $telefono, $email, $note, $file_allegato);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "Errore durante l'aggiunta del paziente.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Aggiungi Paziente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include('../templates/header.php'); ?>

    <div class="container mt-5">
        <h2>Aggiungi Nuovo Paziente</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" class="form-control" id="cognome" name="cognome" required>
            </div>
            <div class="mb-3">
                <label for="data_nascita" class="form-label">Data di Nascita</label>
                <input type="date" class="form-control" id="data_nascita" name="data_nascita" required>
            </div>
            <div class="mb-3">
                <label for="indirizzo" class="form-label">Indirizzo</label>
                <input type="text" class="form-control" id="indirizzo" name="indirizzo" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note (opzionale)</label>
                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File Allegato (opzionale)</label>
                <input type="file" class="form-control" id="file" name="file">
            </div>
            <button type="submit" class="btn btn-success">Aggiungi Paziente</button>
        </form>
    </div>

    <!-- Modal per notifica -->
    <?php if ($success): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Paziente Aggiunto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Il paziente è stato aggiunto con successo! Cosa desideri fare?
                </div>
                <div class="modal-footer">
                    <a href="visualizza_pazienti.php" class="btn btn-primary">Visualizza Pazienti</a>
                    <a href="dashboard.php" class="btn btn-secondary">Torna alla Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Mostra il modal automaticamente al caricamento della pagina
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    </script>
    <?php endif; ?>

</body>
</html>
