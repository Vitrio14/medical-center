<?php
session_start();

// Controlla se è stata confermata l'uscita
if (isset($_POST['confirm_logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Logout</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Sei sicuro di voler uscire?</h2>
        <div class="text-center">
            <!-- Pulsante per aprire il modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                Logout
            </button>
            <a href="dashboard.php" class="btn btn-secondary">Annulla</a>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Conferma Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler uscire? Dovrai effettuare nuovamente il login per accedere.
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        <button type="submit" name="confirm_logout" class="btn btn-danger">Conferma Logout</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
