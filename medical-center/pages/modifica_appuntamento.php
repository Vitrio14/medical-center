<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data_appuntamento = $_POST['data_appuntamento'];
    $note = $_POST['note'];

    $conn = getDBConnection();
    $sql = "UPDATE appuntamenti SET data_appuntamento = ?, note = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $data_appuntamento, $note, $id);
    
    if ($stmt->execute()) {
        echo "Appuntamento modificato con successo!";
    } else {
        echo "Errore nel modificare l'appuntamento.";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = getDBConnection();
    $sql = "SELECT * FROM appuntamenti WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appuntamento = $result->fetch_assoc();
}
?>

<form method="POST" action="modifica_appuntamento.php?id=<?php echo $appuntamento['id']; ?>">
    <label for="data_appuntamento">Data Appuntamento:</label>
    <input type="datetime-local" name="data_appuntamento" value="<?php echo $appuntamento['data_appuntamento']; ?>" required>
    
    <label for="note">Note:</label>
    <textarea name="note"><?php echo $appuntamento['note']; ?></textarea>
    
    <button type="submit">Modifica Appuntamento</button>
</form>
