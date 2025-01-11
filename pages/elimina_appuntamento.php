<?php
// Include file di connessione al database
include_once('../includes/db_connect.php');

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
            echo "<script>
                alert('Appuntamento eliminato con successo!');
                window.location.href = 'gestione_appuntamenti.php';
            </script>";
        } else {
            echo "<script>
                alert('Errore: ID appuntamento non trovato!');
                window.location.href = 'gestione_appuntamenti.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Errore nella preparazione della query: " . htmlspecialchars($conn->error) . "');
            window.location.href = 'gestione_appuntamenti.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Errore: ID appuntamento non fornito!');
        window.location.href = 'gestione_appuntamenti.php';
    </script>";
}
?>
