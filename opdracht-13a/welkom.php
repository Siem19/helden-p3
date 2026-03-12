<?php
session_start();
if(!isset($_SESSION['gebruiker'])) {
    header("Location: login.php");
    exit();
}
$gebruikersnaam = $_SESSION['gebruiker'];
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
<p><a href="logout.php">Uitloggen</a></p>
</body>
</html>