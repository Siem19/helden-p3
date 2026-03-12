<?php
require 'config.php';
session_start();
if(!isset($_SESSION['gebruiker']) || $_SESSION['gebruiker'] != 'admin') {
    die("Toegang geweigerd");
}

$query = $db->query("SELECT username FROM users2");
$gebruikers = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Wachtwoord Wijzigen</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Admin: Wachtwoord Wijzigen</h2>
<form action="verwerkingwachtwoord.php" method="post">
    <label>Gebruiker:</label><br>
    <select name="username" required>
        <option value="">-- Kies een gebruiker --</option>
        <?php foreach($gebruikers as $u): ?>
            <option value="<?= htmlspecialchars($u['username']) ?>"><?= htmlspecialchars($u['username']) ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <label>Nieuw wachtwoord:</label><br>
    <input type="password" name="newPassword" required><br><br>
    <input type="submit" value="Wijzig wachtwoord">
</form>
<a href="welkom.php">Terug naar Dashboard</a>
</body>
</html>