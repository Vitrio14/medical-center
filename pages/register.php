<?php
session_start();
include('../templates/header.php'); // Inclusione della barra di navigazione

include_once('../includes/db_connect.php');
include('navbar.php');

$message = ''; // Variabile per il messaggio del modal
$messageType = ''; // Tipo di messaggio ('success' o 'danger')

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $specializzazione = $_POST['specializzazione']; // Nuovo campo per la specializzazione

    $conn = getDBConnection();
    $sql = "INSERT INTO medici (username, password, nome, cognome, email, specializzazione) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $username, $password, $nome, $cognome, $email, $specializzazione);

    if ($stmt->execute()) {
        $message = 'Registrazione completata con successo!';
        $messageType = 'success';
    } else {
        $message = 'Errore durante la registrazione: ' . $stmt->error;
        $messageType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione Medico</title>
    <!-- Aggiungi il link al CDN di Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Registrazione Medico</h2>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cognome" class="form-label">Cognome:</label>
                <input type="text" name="cognome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="specializzazione" class="form-label">Specializzazione:</label>
                <input type="text" name="specializzazione" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrati</button>
        </form>
    </div>

    <!-- Modal -->
    <?php if (!empty($message)): ?>
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">
                        <?php echo $messageType === 'success' ? 'Successo' : 'Errore'; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <div class="modal-footer">
                    <a href="dashboard.php" class="btn btn-primary">Vai alla Dashboard</a>
                    <a href="visualizza_medici.php" class="btn btn-secondary">Visualizza Medici</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (!empty($message)): ?>
                var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            <?php endif; ?>
        });
    </script>

    <?php include('../templates/footer.php'); // Inclusione del footer ?>
</body>
</html>
