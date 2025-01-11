<?php
session_start();

// Controllo se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../templates/header.php');
include_once('../includes/db_connect.php'); // Inclusione del file per la connessione al database

// Recupera l'elenco dei pazienti dal database
$conn = getDBConnection();

$sql_pazienti = "SELECT id, CONCAT(nome, ' ', cognome) AS nome_completo FROM pazienti";
$result_pazienti = $conn->query($sql_pazienti);
$pazienti = $result_pazienti ? $result_pazienti->fetch_all(MYSQLI_ASSOC) : [];

// Recupera l'elenco dei medici dal database
$sql_medici = "SELECT id, CONCAT(nome, ' ', cognome) AS nome_completo FROM medici";
$result_medici = $conn->query($sql_medici);
$medici = $result_medici ? $result_medici->fetch_all(MYSQLI_ASSOC) : [];

$conn->close(); // Chiude la connessione al database
?>

<div class="container mt-5">
    <h2>Aggiungi Nuovo Appuntamento</h2>
    <form method="POST" action="aggiungi_appuntamento.php">
        <!-- Selezione Paziente -->
        <div class="mb-3">
            <label for="paziente" class="form-label">Seleziona Paziente</label>
            <select class="form-select" id="paziente" name="paziente" required>
                <option value="">-- Seleziona un Paziente --</option>
                <?php foreach ($pazienti as $paziente): ?>
                    <option value="<?php echo htmlspecialchars($paziente['id']); ?>">
                        <?php echo htmlspecialchars($paziente['nome_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Selezione Medico -->
        <div class="mb-3">
            <label for="medico" class="form-label">Seleziona Medico</label>
            <select class="form-select" id="medico" name="medico" required>
                <option value="">-- Seleziona un Medico --</option>
                <?php foreach ($medici as $medico): ?>
                    <option value="<?php echo htmlspecialchars($medico['id']); ?>">
                        <?php echo htmlspecialchars($medico['nome_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Data Appuntamento -->
        <div class="mb-3">
            <label for="data" class="form-label">Data Appuntamento</label>
            <input type="date" class="form-control" id="data" name="data" required>
        </div>

        <!-- Ora Appuntamento -->
        <div class="mb-3">
            <label for="ora" class="form-label">Ora Appuntamento</label>
            <input type="time" class="form-control" id="ora" name="ora" required>
        </div>

        <!-- Note -->
        <div class="mb-3">
            <label for="note" class="form-label">Note (opzionale)</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>

        <!-- Pulsante per inviare -->
        <button type="submit" class="btn btn-success">Aggiungi Appuntamento</button>
    </form>
</div>

<?php include('../templates/footer.php'); ?>
