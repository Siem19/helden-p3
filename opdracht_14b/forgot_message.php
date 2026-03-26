<?php
// forgot_password.php
require_once 'config.php'; // Zorg ervoor dat je configuratiebestand correct is ingesteld

// Hiermee voorkom je dat er toegang wordt geweigerd als de gebruiker niet is ingelogd
session_start();

// Controleer of de gebruiker is ingelogd, anders stuur ze naar de inlogpagina
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Nu hebben we toegang tot de variabelen die zijn gedefinieerd in config.php
try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    // Query om alle gebruikers op te halen
    $query = $db->query("SELECT * FROM users");
    $gebruikers = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Wijzigen (Beheerder)</title>
</head>
<body>
    <h2>Wachtwoord Wijzigen voor Gebruiker</h2>
    <form action="forgot_password_process.php" method="post">
        <div>
            <label for="username">Selecteer Gebruiker:</label><br>
            <select id="username" name="username" required>
            <?php foreach ($gebruikers as $gebruiker): ?>
            <option value="<?= htmlspecialchars($gebruiker['username']) ?>"><?= htmlspecialchars($gebruiker['username']) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="newPassword">Nieuw Wachtwoord:</label><br>
            <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <div>
            <input type="submit" value="Wachtwoord Wijzigen">
        </div>
    </form>
    <?php
    // Als de gebruiker een admin is en de rol is ingesteld, toon een link naar forgot_password.php
    if (isset($_SESSION['gebruikersrol']) && $_SESSION['gebruikersrol'] === 'beheerder') {
        echo "<br><a href='forgot_password.php'>Wachtwoord vergeten? Klik hier</a>";
    }
    ?>
</body>
</html>