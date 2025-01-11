<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');
include('../includes/db_connect.php');

// Recupera gli appuntamenti dal database
$conn = getDBConnection();
$sql = "SELECT a.id, 
               CONCAT(p.nome, ' ', p.cognome) AS paziente, 
               CONCAT(m.nome, ' ', m.cognome) AS medico, 
               a.data_appuntamento, 
               a.ora_appuntamento, 
               a.note 
        FROM appuntamenti a
        JOIN pazienti p ON a.paziente_id = p.id
        JOIN medici m ON a.medico_id = m.id";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Gestione Appuntamenti</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paziente</th>
                <th>Medico</th>
                <th>Data</th>
                <th>Ora</th>
                <th>Note</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appuntamento = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $appuntamento['id']; ?></td>
                <td><?php echo $appuntamento['paziente']; ?></td>
                <td><?php echo $appuntamento['medico']; ?></td>
                <td><?php echo $appuntamento['data_appuntamento']; ?></td>
                <td><?php echo $appuntamento['ora_appuntamento']; ?></td>
                <td><?php echo $appuntamento['note']; ?></td>
                <td>
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
