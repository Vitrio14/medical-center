<?php
session_start();

// Controllo se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once('../includes/db_connect.php'); // Connessione al database
include('navbar.php');

$conn = getDBConnection();

// Query per ottenere tutti i pazienti
$sql = "SELECT id, nome, cognome, data_nascita, indirizzo, telefono, email, note, data_creazione FROM pazienti";
$result = $conn->query($sql);

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
                                // Recupera i file associati al paziente
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
                                <a href="elimina_paziente.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo paziente?');">Elimina</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
