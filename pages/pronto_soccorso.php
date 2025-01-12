
<?php
include_once('../includes/db_connect.php');

// Gestione dell'inserimento dei pazienti
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data_nascita = $_POST['data_nascita'];
    $telefono = $_POST['telefono'];
    $codice_colore = $_POST['codice_colore'];
    $trattamenti = $_POST['trattamenti'];
    $medico_id = $_POST['medico_id'];

    $sql = "INSERT INTO pronto_soccorso (nome, cognome, data_nascita, telefono, codice_colore, trattamenti, medico_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nome, $cognome, $data_nascita, $telefono, $codice_colore, $trattamenti, $medico_id);
    if ($stmt->execute()) {
        $message = "Paziente aggiunto con successo!";
    } else {
        $message = "Errore nell'aggiunta del paziente.";
    }
    $stmt->close();
}

// Recupero dei dati dei pazienti
$sql = "SELECT ps.*, m.nome AS nome_medico, m.cognome AS cognome_medico 
        FROM pronto_soccorso ps 
        LEFT JOIN medici m ON ps.medico_id = m.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestione Pronto Soccorso</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Gestione Pronto Soccorso</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Tabella dei pazienti -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Data di Nascita</th>
                    <th>Telefono</th>
                    <th>Codice Colore</th>
                    <th>Trattamenti</th>
                    <th>Medico</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['cognome']; ?></td>
                        <td><?php echo $row['data_nascita']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['codice_colore']; ?></td>
                        <td><?php echo $row['trattamenti']; ?></td>
                        <td><?php echo $row['nome_medico'] . " " . $row['cognome_medico']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Modale per aggiungere pazienti -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">Aggiungi Paziente</button>

        <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPatientModalLabel">Aggiungi Paziente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="cognome" class="form-label">Cognome</label>
                                <input type="text" class="form-control" id="cognome" name="cognome" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_nascita" class="form-label">Data di Nascita</label>
                                <input type="date" class="form-control" id="data_nascita" name="data_nascita" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Telefono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="codice_colore" class="form-label">Codice Colore</label>
                                <select class="form-select" id="codice_colore" name="codice_colore" required>
                                    <option value="Rosso">Rosso</option>
                                    <option value="Giallo">Giallo</option>
                                    <option value="Verde">Verde</option>
                                    <option value="Bianco">Bianco</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="trattamenti" class="form-label">Trattamenti</label>
                                <textarea class="form-control" id="trattamenti" name="trattamenti" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="medico_id" class="form-label">Medico</label>
                                <select class="form-select" id="medico_id" name="medico_id" required>
                                    <?php
                                    $medici = $conn->query("SELECT id, nome, cognome FROM medici");
                                    while ($medico = $medici->fetch_assoc()) {
                                        echo '<option value="' . $medico['id'] . '">' . $medico['nome'] . ' ' . $medico['cognome'] . '</option>';

                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <button type="submit" class="btn btn-primary">Salva</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
