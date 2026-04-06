<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "poll";

try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
    // Zet de PDO error modus op exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>