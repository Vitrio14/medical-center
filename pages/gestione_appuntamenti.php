<?php
include('../templates/header.php');
include('../includes/db_connect.php');

// Recupera gli appuntamenti dal database
$conn = getDBConnection();
$sql = "SELECT * FROM appuntamenti";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Gestione Appuntamenti</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paziente</th>
                <th>Data</th>
                <th>Ora</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appuntamento = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $appuntamento['id']; ?></td>
                <td><?php echo $appuntamento['paziente']; ?></td>
                <td><?php echo $appuntamento['data']; ?></td>
                <td><?php echo $appuntamento['ora']; ?></td>
                <td>
                    <!-- Bottone di eliminazione -->
                    <form method="POST" action="elimina_appuntamento.php" style="display:inline;">
                        <input type="hidden" name="appuntamento_id" value="<?php echo $appuntamento['id']; ?>">
                        <button type="submit" class="btn btn-danger">Elimina</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../templates/footer.php'); ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success">Appuntamento eliminato con successo!</div>
<?php endif; ?>
