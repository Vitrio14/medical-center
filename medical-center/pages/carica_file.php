<?php
include_once('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $paziente_id = $_POST['paziente_id'];
    $file = $_FILES['file'];
    
    $upload_dir = '../uploads/';
    $file_path = $upload_dir . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $conn = getDBConnection();
        $sql = "INSERT INTO file (paziente_id, file_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $paziente_id, $file_path);
        if ($stmt->execute()) {
            $success_message = "File caricato con successo!";
        } else {
            $error_message = "Errore nel caricare il file.";
        }
    } else {
        $error_message = "Errore durante il caricamento del file.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica File</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Carica File per il Paziente</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" action="carica_file.php">
            <div class="mb-3">
                <label for="paziente_id" class="form-label">ID Paziente</label>
                <input type="number" class="form-control" id="paziente_id" name="paziente_id" value="1" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Seleziona File</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <button type="submit" class="btn btn-primary">Carica File</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
