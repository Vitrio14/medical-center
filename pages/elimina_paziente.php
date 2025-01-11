<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php'); // Connessione al database

if (!isset($_GET['id'])) {
    die("ID paziente non specificato.");
}

$id = $_GET['id'];
$conn = getDBConnection();

// Recupera i file associati al paziente
$sql_files = "SELECT nome_file FROM pazienti_file WHERE paziente_id = ?";
$stmt_files = $conn->prepare($sql_files);
$stmt_files->bind_param("i", $id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();

// Elimina i file associati dal filesystem
$upload_dir = '../uploads/';
while ($file = $result_files->fetch_assoc()) {
    $file_path = $upload_dir . $file['nome_file'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Elimina i record dei file dal database
$sql_delete_files = "DELETE FROM pazienti_file WHERE paziente_id = ?";
$stmt_delete_files = $conn->prepare($sql_delete_files);
$stmt_delete_files->bind_param("i", $id);
$stmt_delete_files->execute();

// Elimina il paziente dal database
$sql_delete_paziente = "DELETE FROM pazienti WHERE id = ?";
$stmt_delete_paziente = $conn->prepare($sql_delete_paziente);
$stmt_delete_paziente->bind_param("i", $id);

if ($stmt_delete_paziente->execute()) {
    // Reindirizzamento con parametro di successo
    header('Location: visualizza_pazienti.php?success=elimina');
    exit();
} else {
    die("Errore durante l'eliminazione del paziente.");
}
?>
