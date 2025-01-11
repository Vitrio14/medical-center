<?php
session_start();

function checkSession() {
    if (!isset($_SESSION['medico_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>
