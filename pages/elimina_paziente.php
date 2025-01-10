<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn = getDBConnection();
    $sql = "DELETE FROM pazienti WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo "Paziente eliminato con successo!";
    } else {
        echo "Errore nell'eliminare il paziente.";
    }
}
?>
