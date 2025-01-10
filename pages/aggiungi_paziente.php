<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');
?>

<!-- Form di aggiunta paziente -->
<div class="container mt-5">
    <h2>Aggiungi Nuovo Paziente</h2>
    <form method="POST" action="aggiungi_paziente.php">
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
            <input type="tel" class="form-control" id="telefono" name="telefono" required>
        </div>
        <button type="submit" class="btn btn-success">Aggiungi Paziente</button>
    </form>
</div>

<?php include('../templates/footer.php'); ?>
