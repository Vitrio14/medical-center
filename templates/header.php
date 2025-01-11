<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Center</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../dashboard.php">Medical Center</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/visualizza_pazienti.php">Visualizza Pazienti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/aggiungi_paziente.php">Aggiungi Paziente</a>
                </li>
                <li class="nav-item">
        <a class="nav-link" href="gestione_appuntamenti.php">Gestione Appuntamenti</a>
    </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/aggiungi_appuntamento.php">Aggiungi Appuntamento</a> <!-- Nuovo elemento -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/register.php">Registrazione Medici</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

