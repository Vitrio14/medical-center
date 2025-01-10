<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');

// Supponiamo di avere un elenco di pazienti dal database
$pazienti = [
    ['id' => 1, 'nome' => 'Mario Rossi'],
    ['id' => 2, 'nome' => 'Giulia Bianchi']
];

?>

<div class="container mt-5">
    <h2>Aggiungi Nuovo Appuntamento</h2>
    <form method="POST" action="aggiungi_appuntamento.php">
        <div class="mb-3">
            <label for="paziente" class="form-label">Seleziona Paziente</label>
            <select class="form-select" id="paziente" name="paziente" required>
                <option value="">-- Seleziona un Paziente --</option>
                <?php foreach ($pazienti as $paziente): ?>
                    <option value="<?php echo $paziente['id']; ?>"><?php echo $paziente['nome']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="data" class="form-label">Data Appuntamento</label>
            <input type="date" class="form-control" id="data" name="data" required>
        </div>

        <div class="mb-3">
            <label for="ora" class="form-label">Ora Appuntamento</label>
            <input type="time" class="form-control" id="ora" name="ora" required>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note (opzionale)</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Aggiungi Appuntamento</button>
    </form>
</div>

<?php include('../templates/footer.php'); ?>
