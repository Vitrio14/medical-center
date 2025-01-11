<?php
include_once('../includes/db_connect.php');
include_once('../includes/session.php');
include('navbar.php');

// Controlla se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se è stato fornito un ID medico
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Errore: ID medico non specificato.");
}

$id = intval($_GET['id']);

// Connessione al database
$conn = getDBConnection();

// Recupera i dettagli del medico
$sql = "SELECT * FROM medici WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$medico = $result->fetch_assoc();

if (!$medico) {
    die("Errore: Medico non trovato.");
}

// Se il modulo è stato inviato, aggiorna i dettagli del medico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $specializzazione = $_POST['specializzazione'];

    // Verifica che i campi obbligatori siano compilati
    if (empty($nome) || empty($cognome) || empty($email)) {
        echo "<div class='alert alert-danger'>Errore: Tutti i campi obbligatori devono essere compilati.</div>";
    } else {
        // Aggiorna i dati del medico
        $sql_update = "UPDATE medici 
                       SET nome = ?, cognome = ?, email = ?, specializzazione = ? 
                       WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('ssssi', $nome, $cognome, $email, $specializzazione, $id);

        if ($stmt_update->execute()) {
            echo "<div class='alert alert-success'>Dettagli del medico aggiornati con successo.</div>";
        } else {
            echo "<div class='alert alert-danger'>Errore nell'aggiornamento del medico.</div>";
        }
    }
}

// Chiudi la connessione al database
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Modifica Medico</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Modifica Medico</h2>
        <form method="POST" action="modifica_medico.php?id=<?php echo htmlspecialchars($id); ?>">
            <!-- Nome -->
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" 
                       value="<?php echo htmlspecialchars($medico['nome']); ?>" required>
            </div>

            <!-- Cognome -->
            <div class="mb-3">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" class="form-control" id="cognome" name="cognome" 
                       value="<?php echo htmlspecialchars($medico['cognome']); ?>" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($medico['email']); ?>" required>
            </div>

            <!-- Specializzazione -->
            <div class="mb-3">
                <label for="specializzazione" class="form-label">Specializzazione</label>
                <input type="text" class="form-control" id="specializzazione" name="specializzazione" 
                       value="<?php echo htmlspecialchars($medico['specializzazione']); ?>">
            </div>

            <!-- Pulsante per salvare -->
            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
            <a href="visualizza_medici.php" class="btn btn-secondary">Annulla</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
