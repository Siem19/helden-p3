<?php
// Verbinding maken met de database
$conn = new mysqli("localhost", "gebruikersnaam", "wachtwoord", "database");

// Controleren op fouten
if ($conn->connect_error) {
    die("Connectie mislukt: " . $conn->connect_error);
}

// Gegevens van het registratieformulier ophalen
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Wachtwoord hashen voor veiligheid

// SQL-query om de gebruiker toe te voegen aan de database
$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Registratie succesvol!";
} else {
    echo "Fout bij registratie: " . $conn->error;
}

// Verbinding met de database sluiten
$conn->close();
?>