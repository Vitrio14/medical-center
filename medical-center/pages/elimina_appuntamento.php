<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db_connect.php');

// Verifica se l'ID dell'appuntamento è stato passato
if (isset($_POST['appuntamento_id'])) {
    $appuntamento_id = $_POST['appuntamento_id'];

    // Connetti al database
    $conn = getDBConnection();

    // Prepara la query di eliminazione
    $sql = "DELETE FROM appuntamenti WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $appuntamento_id);

    // Esegui la query
    if ($stmt->execute()) {
        // Redirigi alla pagina di gestione appuntamenti con un messaggio di successo
        header('Location: gestione_appuntamenti.php?success=1');
        exit();
    } else {
        // Se si verifica un errore, mostra un messaggio
        echo "<div class='alert alert-danger'>Errore nell'eliminazione dell'appuntamento.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>ID appuntamento non trovato.</div>";
}
?>
