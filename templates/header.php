<?php
// Avvia la sessione solo se non è già attiva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connessione al database
include_once('../includes/db_connect.php');

// Connessione al database
$conn = getDBConnection();

// Query per ottenere il numero di pazienti
$sql = "SELECT COUNT(*) as total FROM pazienti";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalPazienti = $row['total'];

// Chiudi la connessione
$conn->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Center</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar o menu di navigazione -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Medical Center</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aggiungi_paziente.php">Aggiungi Paziente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aggiungi_appuntamento.php">Aggiungi Appuntamento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gestione_appuntamenti.php">Gestione Appuntamenti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <!-- Mostra il numero di pazienti registrati -->
                    <li class="nav-item">
                        <a class="nav-link" href="visualizza_pazienti.php">Pazienti <span class="badge bg-primary"><?php echo $totalPazienti; ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
