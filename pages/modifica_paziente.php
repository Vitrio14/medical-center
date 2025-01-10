<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');

// Recupera i dati del paziente (esempio)
$paziente_id = $_GET['id'];
// Fetch dati paziente dal database (qui come esempio statico)
$paziente = [
    'nome' => 'Mario',
    'cognome' => 'Rossi',
    'data_nascita' => '1980-01-01',
    'telefono' => '123456789'
];
?>

<div class="container mt-5">
    <h2>Modifica Paziente: <?php echo $paziente['nome'] . ' ' . $paziente['cognome']; ?></h2>
    <form method="POST" action="modifica_paziente.php?id=<?php echo $paziente_id; ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $paziente['nome']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" class="form-control" id="cognome" name="cognome" value="<?php echo $paziente['cognome']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="data_nascita" class="form-label">Data di Nascita</label>
            <input type="date" class="form-control" id="data_nascita" name="data_nascita" value="<?php echo $paziente['data_nascita']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Telefono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo $paziente['telefono']; ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Modifica Paziente</button>
    </form>
</div>

<?php include('../templates/footer.php'); ?>
