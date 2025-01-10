<?php
// Controlla se la funzione è già definita per evitare dichiarazioni multiple
if (!function_exists('getDBConnection')) {
    function getDBConnection() {
        // Modifica con le tue credenziali di database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "medical_center"; // Nome del tuo database

        // Crea la connessione
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica la connessione
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>
