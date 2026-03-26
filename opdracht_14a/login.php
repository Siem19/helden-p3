<?php
require_once('config.php');

// Inloggen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gebruikersnaam en wachtwoord uit het formulier ophalen
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query om de gebruiker op te halen uit de database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    // Controleer of er een gebruiker met de opgegeven gebruikersnaam is gevonden
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Controleer of het ingevoerde wachtwoord overeenkomt met het gehashte wachtwoord in de database
        if (password_verify($password, $row['password'])) {
            // Start een sessie en sla gebruikersgegevens op in de sessievariabelen
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Controleer de rol van de gebruiker
            if ($row['is_admin'] == 1) {
                $_SESSION['role'] = 'admin';
            } else {
                $_SESSION['role'] = 'user';
            }

            // Stuur de gebruiker door naar de hoofdpagina
            header("Location: index.php");
            exit();
        } else {
            // Toon een foutmelding als de ingevoerde gebruikersnaam of wachtwoord onjuist is
            echo "Ongeldige gebruikersnaam of wachtwoord.";
        }
    } else {
        echo "Ongeldige gebruikersnaam of wachtwoord.";
    }
}
 else {
    // Toon een foutmelding als de ingevoerde gebruikersnaam of wachtwoord onjuist is
    echo "Ongeldige gebruikersnaam of wachtwoord.";
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
</head>
<body>
    <h2>Inloggen</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Inloggen</button>
    </form>
    <br>
    <p>Nog geen account? <a href="register.php">Registreren</a></p>
</body>
</html>