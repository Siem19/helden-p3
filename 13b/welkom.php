<?php
session_start();
if(!isset($_SESSION['gebruiker'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

$gebruikersnaam = $_SESSION['gebruiker'];

// Controleer of de gebruiker al een 2FA secret heeft
$stmt = $db->prepare("SELECT 2fa_secret FROM users2 WHERE username = :username");
$stmt->bindParam(':username', $gebruikersnaam);
$stmt->execute();
$user = $stmt->fetch();

$heeft2FA = !empty($user['2fa_secret']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Welkom, <?= htmlspecialchars($gebruikersnaam) ?></h1>

<?php if(!$heeft2FA): ?>
    <p>Je hebt nog geen 2FA ingesteld. <a href="qr.php">Klik hier om 2FA in te stellen</a></p>
<?php else: ?>
    <p>Je hebt 2FA al ingesteld. 👍</p>
<?php endif; ?>

<p><a href="logout.php">Uitloggen</a></p>

</body>
</html>