<?php
require_once('config.php');

// Registratie
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gebruikersgegevens uit het formulier ophalen
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Controleer of de gebruikersnaam al bestaat in de database
    $check_sql = "SELECT * FROM users WHERE username='$username'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        // Gebruikersnaam bestaat al, toon foutmelding
        echo "Gebruikersnaam is al in gebruik.";
    } else {
        // Gebruikersnaam is uniek, voeg gebruiker toe aan de database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Registratie succesvol!";
        } else {
            echo "Fout bij registratie: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratie</title>
</head>
<body>
    <h2>Registratie</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Registreren</button>
    </form>
    <br>
    <p>Heb je al een account? <a href="login.php">Inloggen</a></p>
</body>
</html>