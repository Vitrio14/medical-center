<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php'); // Connessione al database
include('navbar.php');

$conn = getDBConnection();

if (!isset($_GET['id'])) {
    die("ID paziente non specificato.");
}

$id = $_GET['id'];

// Recupera i dati del paziente
$sql = "SELECT * FROM pazienti WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Paziente non trovato.");
}

$paziente = $result->fetch_assoc();

// Recupera i file associati al paziente
$sql_files = "SELECT * FROM pazienti_file WHERE paziente_id = ?";
$stmt_files = $conn->prepare($sql_files);
$stmt_files->bind_param("i", $id);
$stmt_files->execute();
$files_result = $stmt_files->get_result();
$files = $files_result->fetch_all(MYSQLI_ASSOC);

// Gestione del modulo di modifica
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data_nascita = $_POST['data_nascita'];
    $indirizzo = $_POST['indirizzo'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $note = $_POST['note'];

    // Aggiorna i dati del paziente
    $sql = "UPDATE pazienti SET nome = ?, cognome = ?, data_nascita = ?, indirizzo = ?, telefono = ?, email = ?, note = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi', $nome, $cognome, $data_nascita, $indirizzo, $telefono, $email, $note, $id);

    if ($stmt->execute()) {
        $success = "Paziente aggiornato con successo.";

        // Gestione dei file caricati
        if (!empty($_FILES['files']['name'][0])) {
            $upload_dir = '../uploads/';
            foreach ($_FILES['files']['name'] as $key => $file_name) {
                $target_file = $upload_dir . basename($file_name);
                if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $target_file)) {
                    $sql_file = "INSERT INTO pazienti_file (paziente_id, nome_file) VALUES (?, ?)";
                    $stmt_file = $conn->prepare($sql_file);
                    $stmt_file->bind_param("is", $id, $file_name);
                    $stmt_file->execute();
                }
            }
        }

        // Rimuovi file selezionati
        if (isset($_POST['remove_files'])) {
            foreach ($_POST['remove_files'] as $file_id) {
                $sql_remove = "SELECT nome_file FROM pazienti_file WHERE id = ?";
                $stmt_remove = $conn->prepare($sql_remove);
                $stmt_remove->bind_param("i", $file_id);
                $stmt_remove->execute();
                $result_remove = $stmt_remove->get_result();
                $file_to_delete = $result_remove->fetch_assoc();

                if ($file_to_delete) {
                    unlink($upload_dir . $file_to_delete['nome_file']);
                    $sql_delete = "DELETE FROM pazienti_file WHERE id = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $file_id);
                    $stmt_delete->execute();
                }
            }
        }
    } else {
        $error = "Errore durante l'aggiornamento del paziente.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Paziente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../templates/header.php'); ?>

    <div class="container mt-5">
        <h2>Modifica Paziente</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Campi paziente -->
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $paziente['nome']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" class="form-control" id="cognome" name="cognome" value="<?php echo $paziente['cognome']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_nascita" class="form-label">Data di Nascita</label>
                <input type="date" class="form-control" id="data_nascita" name="data_nascita" value="<?php echo $paziente['data_nascita']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="indirizzo" class="form-label">Indirizzo</label>
                <input type="text" class="form-control" id="indirizzo" name="indirizzo" value="<?php echo $paziente['indirizzo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $paziente['telefono']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $paziente['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea class="form-control" id="note" name="note" rows="3"><?php echo $paziente['note']; ?></textarea>
            </div>

            <!-- File multipli -->
            <div class="mb-3">
                <label for="files" class="form-label">Allega Nuovi File (opzionale)</label>
                <input type="file" class="form-control" id="files" name="files[]" multiple>
            </div>

            <!-- File esistenti -->
            <?php if ($files): ?>
                <div class="mb-3">
                    <label class="form-label">File Allegati</label>
                    <ul>
                        <?php foreach ($files as $file): ?>
                            <li>
                                <a href="../uploads/<?php echo $file['nome_file']; ?>" target="_blank"><?php echo $file['nome_file']; ?></a>
                                <input type="checkbox" name="remove_files[]" value="<?php echo $file['id']; ?>"> Rimuovi
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Aggiorna Paziente</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
