<?php
session_start();

// Controllo se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php'); // Connessione al database
include('navbar.php');

// Connessione al database
$conn = getDBConnection();
if (!$conn) {
    die("Errore di connessione al database.");
}

// Inizializza il risultato della query
$result = false;

// Query per ottenere tutti i pazienti
$sql = "SELECT id, nome, cognome, data_nascita, indirizzo, telefono, email, note, data_creazione FROM pazienti";
$result = $conn->query($sql);

// Controllo errori nella query
if (!$result) {
    die("Errore durante l'esecuzione della query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Visualizza Pazienti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../templates/header.php'); ?>

    <div class="container mt-5">
        <h2>Elenco Pazienti Registrati</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Data di Nascita</th>
                        <th>Indirizzo</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Note</th>
                        <th>Data Creazione</th>
                        <th>File</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['cognome']; ?></td>
                            <td><?php echo $row['data_nascita']; ?></td>
                            <td><?php echo $row['indirizzo']; ?></td>
                            <td><?php echo $row['telefono']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['note']; ?></td>
                            <td><?php echo $row['data_creazione']; ?></td>
                            <td>
                                <?php
                                $paziente_id = $row['id'];
                                $sql_files = "SELECT nome_file FROM pazienti_file WHERE paziente_id = ?";
                                $stmt_files = $conn->prepare($sql_files);
                                $stmt_files->bind_param("i", $paziente_id);
                                $stmt_files->execute();
                                $files_result = $stmt_files->get_result();

                                if ($files_result->num_rows > 0): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton<?php echo $row['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            File
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $row['id']; ?>">
                                            <?php while ($file = $files_result->fetch_assoc()): ?>
                                                <li><a class="dropdown-item" href="../uploads/<?php echo $file['nome_file']; ?>" target="_blank"><?php echo $file['nome_file']; ?></a></li>
                                            <?php endwhile; ?>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    Nessun file
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="modifica_paziente.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifica</a>
                                <button class="btn btn-danger btn-sm" onclick="showDeletePopup(<?php echo $row['id']; ?>)">Elimina</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Nessun paziente registrato.</div>
        <?php endif; ?>

        <a href="aggiungi_paziente.php" class="btn btn-primary mt-3">Aggiungi Nuovo Paziente</a>
    </div>

    <!-- Modale per conferma modifica -->
    <div class="modal fade" id="modificaModal" tabindex="-1" aria-labelledby="modificaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificaModalLabel">Paziente Modificato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Il paziente è stato modificato con successo.
                </div>
                <div class="modal-footer">
                    <a href="dashboard.php" class="btn btn-primary">Torna alla Dashboard</a>
                    <a href="visualizza_pazienti.php" class="btn btn-secondary">Visualizza Pazienti</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale per conferma eliminazione -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminaModalLabel">Paziente Eliminato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Il paziente è stato eliminato con successo.
                </div>
                <div class="modal-footer">
                    <a href="dashboard.php" class="btn btn-primary">Torna alla Dashboard</a>
                    <a href="visualizza_pazienti.php" class="btn btn-secondary">Visualizza Pazienti</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal per conferma eliminazione singola -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Conferma Eliminazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler eliminare questo paziente?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Elimina</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeletePopup(id) {
            const confirmDelete = document.getElementById('confirmDelete');
            confirmDelete.href = `elimina_paziente.php?id=${id}`;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');

            if (success === 'modifica') {
                const modificaModal = new bootstrap.Modal(document.getElementById('modificaModal'));
                modificaModal.show();
            } else if (success === 'elimina') {
                const eliminaModal = new bootstrap.Modal(document.getElementById('eliminaModal'));
                eliminaModal.show();
            }
        });
    </script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
