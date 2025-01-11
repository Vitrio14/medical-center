<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');
include('navbar.php');

// Controlla se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connessione al database
$conn = getDBConnection();

// Recupera tutti gli appuntamenti
$sql = "SELECT a.id, 
               a.data_appuntamento, 
               a.ora_appuntamento, 
               a.note, 
               a.stato, 
               CONCAT(p.nome, ' ', p.cognome) AS paziente, 
               CONCAT(m.nome, ' ', m.cognome) AS medico 
        FROM appuntamenti a
        JOIN pazienti p ON a.paziente_id = p.id
        JOIN medici m ON a.medico_id = m.id";
$result = $conn->query($sql);

// Chiudi la connessione al database
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Gestione Appuntamenti</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Gestione Appuntamenti</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paziente</th>
                        <th>Medico</th>
                        <th>Data</th>
                        <th>Ora</th>
                        <th>Note</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['paziente']); ?></td>
                            <td><?php echo htmlspecialchars($row['medico']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_appuntamento']); ?></td>
                            <td><?php echo htmlspecialchars($row['ora_appuntamento']); ?></td>
                            <td><?php echo htmlspecialchars($row['note']); ?></td>
                            <td><?php echo htmlspecialchars($row['stato']); ?></td>
                            <td>
                                <!-- Pulsante per modificare -->
                                <a href="modifica_appuntamento.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifica</a>
                                
                                <!-- Pulsante per eliminare -->
                                <button 
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-id="<?php echo $row['id']; ?>">
                                    Elimina
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">Nessun appuntamento registrato.</div>
        <?php endif; ?>

        <a href="aggiungi_appuntamento.php" class="btn btn-success">Aggiungi Nuovo Appuntamento</a>
    </div>

    <!-- Modal per conferma eliminazione -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Conferma Eliminazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler eliminare questo appuntamento?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Conferma</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteModal = document.getElementById('deleteModal');
            var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // Assegna l'ID al pulsante "Conferma" quando si apre il modal
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Pulsante che ha attivato il modal
                var appointmentId = button.getAttribute('data-id'); // ID dell'appuntamento
                confirmDeleteBtn.href = "elimina_appuntamento.php?id=" + appointmentId;
            });
        });
    </script>
</body>
</html>
