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
            echo "File caricato con successo!";
        } else {
            echo "Errore nel caricare il file.";
        }
    }
}
?>

<form method="POST" enctype="multipart/form-data" action="carica_file.php">
    <input type="hidden" name="paziente_id" value="1">
    <input type="file" name="file" required>
    <button type="submit">Carica File</button>
</form>
