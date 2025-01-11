<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');
include_once('../includes/db_connect.php');

// Connessione al database
$conn = getDBConnection();

// Contare i pazienti totali
$sql_pazienti = "SELECT COUNT(*) AS total_pazienti FROM pazienti";
$result_pazienti = $conn->query($sql_pazienti);
$total_pazienti = ($result_pazienti->num_rows > 0) ? $result_pazienti->fetch_assoc()['total_pazienti'] : 0;

// Contare gli appuntamenti di oggi
$today = date('Y-m-d');
$sql_appuntamenti = "SELECT COUNT(*) AS total_appuntamenti FROM appuntamenti WHERE data_appuntamento = ?";
$stmt_appuntamenti = $conn->prepare($sql_appuntamenti);
$stmt_appuntamenti->bind_param('s', $today);
$stmt_appuntamenti->execute();
$result_appuntamenti = $stmt_appuntamenti->get_result();
$total_appuntamenti = ($result_appuntamenti->num_rows > 0) ? $result_appuntamenti->fetch_assoc()['total_appuntamenti'] : 0;

// Contare i medici registrati
$sql_medici = "SELECT COUNT(*) AS total_medici FROM medici";
$result_medici = $conn->query($sql_medici);
$total_medici = ($result_medici->num_rows > 0) ? $result_medici->fetch_assoc()['total_medici'] : 0;

?>

<div class="container mt-5">
    <h1>Benvenuto nel Medical Center!</h1>
    <p>Questa è la tua dashboard. Puoi navigare tra le funzionalità usando il menu in alto.</p>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Pazienti Totali</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_pazienti; ?></h5>
                    <p class="card-text">Numero totale di pazienti registrati.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Appuntamenti Oggi</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_appuntamenti; ?></h5>
                    <p class="card-text">Numero di appuntamenti fissati per oggi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Medici Registrati</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_medici; ?></h5>
                    <p class="card-text">Numero di medici attualmente registrati.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../templates/footer.php'); ?>
