<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');
?>

<!-- Contenuto della Dashboard -->
<div class="container mt-5">
    <h1>Benvenuto nel Medical Center!</h1>
    <p>Questa è la tua dashboard. Puoi navigare tra le funzionalità usando il menu in alto.</p>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Pazienti Totali</div>
                <div class="card-body">
                    <h5 class="card-title">120</h5>
                    <p class="card-text">Numero totale di pazienti registrati.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Appuntamenti Oggi</div>
                <div class="card-body">
                    <h5 class="card-title">15</h5>
                    <p class="card-text">Numero di appuntamenti fissati per oggi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Medici Registrati</div>
                <div class="card-body">
                    <h5 class="card-title">5</h5>
                    <p class="card-text">Numero di medici attualmente registrati.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../templates/footer.php'); ?>
