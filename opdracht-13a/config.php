<?php
// config.php - databaseconfiguratie

$host = 'localhost';      // database server
$dbname = 'fietsenmaker'; // naam van je database
$user = 'root';           // database gebruiker
$pass = '';               // wachtwoord, leeg op lokale server

// DSN voor PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Opties voor PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // Fouten tonen
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Fetch als associative array
    PDO::ATTR_EMULATE_PREPARES => false,                  // echte prepared statements
];

try {
    $db = new PDO($dsn, $user, $pass, $options);
    // Als je dit ziet, is de verbinding gelukt:
    // echo "Databaseverbinding succesvol!";
} catch (PDOException $e) {
    die("Verbinding mislukt: " . $e->getMessage());
}
?>