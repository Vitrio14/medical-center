<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header.php');
include('navbar.php');
include_once('../includes/db_connect.php');

// Connessione al database
$conn = getDBConnection();

// Contare i pazienti totali
$sql_pazienti = "SELECT COUNT(*) AS total_pazienti FROM pazienti";
$result_pazienti = $conn->query($sql_pazienti);
$total_pazienti = ($result_pazienti->num_rows > 0) ? $result_pazienti->fetch_assoc()['total_pazienti'] : 0;

// Contare gli appuntamenti totali
$sql_appuntamenti_totali = "SELECT COUNT(*) AS total_appuntamenti FROM appuntamenti";
$result_appuntamenti_totali = $conn->query($sql_appuntamenti_totali);
$total_appuntamenti_totali = ($result_appuntamenti_totali->num_rows > 0) ? $result_appuntamenti_totali->fetch_assoc()['total_appuntamenti'] : 0;

// Contare gli appuntamenti di oggi (considerando i timestamp)
$today = date('Y-m-d'); // Formato YYYY-MM-DD
$today_start = $today . " 00:00:00"; // Inizio giornata
$today_end = $today . " 23:59:59";   // Fine giornata

$sql_appuntamenti_oggi = "SELECT COUNT(*) AS total_appuntamenti_oggi FROM appuntamenti WHERE data_appuntamento >= ? AND data_appuntamento <= ?";
$stmt_appuntamenti_oggi = $conn->prepare($sql_appuntamenti_oggi);

if ($stmt_appuntamenti_oggi === false) {
    die("Errore nella preparazione della query: " . $conn->error);
}

$stmt_appuntamenti_oggi->bind_param('ss', $today_start, $today_end);
$stmt_appuntamenti_oggi->execute();
$result_appuntamenti_oggi = $stmt_appuntamenti_oggi->get_result();

if ($result_appuntamenti_oggi === false) {
    die("Errore nella query: " . $stmt_appuntamenti_oggi->error);
}

$total_appuntamenti_oggi = ($result_appuntamenti_oggi->num_rows > 0) ? $result_appuntamenti_oggi->fetch_assoc()['total_appuntamenti_oggi'] : 0;

// Contare i medici registrati
$sql_medici = "SELECT COUNT(*) AS total_medici FROM medici";
$result_medici = $conn->query($sql_medici);
$total_medici = ($result_medici->num_rows > 0) ? $result_medici->fetch_assoc()['total_medici'] : 0;
?>

<div class="container mt-5">
    <h1>Benvenuto nel gestionale EMS, dottore!</h1>
    <p>Questa è la tua dashboard. Puoi navigare tra le funzionalità usando il menu in alto.</p>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Pazienti Totali</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_pazienti; ?></h5>
                    <p class="card-text">Numero totale di pazienti registrati.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Appuntamenti Totali</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_appuntamenti_totali; ?></h5>
                    <p class="card-text">Numero totale di appuntamenti registrati.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
