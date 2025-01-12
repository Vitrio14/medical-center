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

// Recupera tutti i medici
$sql = "SELECT id, nome, cognome, email, specializzazione, data_creazione FROM medici";
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
    <title>Visualizza Medici</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Elenco Medici</h2>

        <!-- Modale per notifiche -->
        <?php if (isset($_SESSION['success_message']) || isset($_SESSION['error_message'])): ?>
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">
                            <?php echo isset($_SESSION['success_message']) ? 'Successo' : 'Errore'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php 
                        echo htmlspecialchars($_SESSION['success_message'] ?? $_SESSION['error_message']); 
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a href="dashboard.php" class="btn btn-primary">Torna alla Dashboard</a>
                        <a href="visualizza_medici.php" class="btn btn-secondary">Visualizza Medici</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
                notificationModal.show();
            });
        </script>
        <?php 
        unset($_SESSION['success_message'], $_SESSION['error_message']); 
        ?>
        <?php endif; ?>

        <!-- Tabella medici -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Specializzazione</th>
                    <th>Data Creazione</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['cognome']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['specializzazione']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_creazione']); ?></td>
                            <td>
                                <!-- Pulsante per modificare -->
                                <a href="modifica_medico.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifica</a>

                                <!-- Pulsante per eliminare -->
                                <button type="button" class="btn btn-danger btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                        data-id="<?php echo $row['id']; ?>">Elimina</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Nessun medico trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modale di conferma eliminazione -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Conferma Eliminazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler eliminare questo medico?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="elimina_medico.php">
                        <!-- Campo nascosto per l'ID del medico -->
                        <input type="hidden" name="id" id="medicoId" value="">
                        <button type="submit" class="btn btn-danger">Conferma</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap e JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aggiunge l'ID del medico al campo nascosto nella modale
        document.addEventListener('DOMContentLoaded', function () {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var medicoId = button.getAttribute('data-id');
                var inputId = deleteModal.querySelector('#medicoId');
                inputId.value = medicoId;
            });
        });
    </script>
</body>
</html>
