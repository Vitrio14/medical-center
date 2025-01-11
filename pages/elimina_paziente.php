<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = getDBConnection();
    $sql = "DELETE FROM pazienti WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: visualizza_pazienti.php');
        exit();
    } else {
        echo "Errore durante l'eliminazione del paziente.";
    }
}
?>
