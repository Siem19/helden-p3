<?php
// index.php
require('config.php');

// Controleren of de gebruiker is ingelogd
session_start();
if (!isset($_SESSION['user_id'])) {
    // Als de gebruiker niet is ingelogd, stuur ze door naar de inlogpagina
    header("Location: login.php");
    exit();
}

// Controleren of de gebruiker een admin is
$is_admin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $is_admin = true;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gastenboek</title>
</head>
<body>
<h2>Gastenboek</h2>

<p>Welkom, <?php echo $_SESSION['username']; ?> !</p>

<?php
// Query om alle berichten op te halen
$sql = "SELECT * FROM gastboek";
$result = $conn->query($sql);

// Controleer op fouten bij de query-uitvoering
if (!$result) {
    die("Query mislukt: " . $conn->error);
}

// Controleer of er rijen zijn opgehaald
if ($result->num_rows > 0) {
    // Loop door elk bericht en geef ze weer
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>Gebruikersnaam:</strong> " . $row["username"] . "</p>";
        echo "<p><strong>Bericht:</strong> " . $row["message"] . "</p>";

        // Buttons voor bewerken en verwijderen
        if ($is_admin || ($_SESSION['user_id'] == $row['user_id'])) {
            echo "<form action='edit_message.php' method='get' style='display:inline;'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<button type='submit'>Bewerken</button>";
            echo "</form>";

            echo "<form action='delete_message.php' method='get' style='display:inline;'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<button type='submit'>Verwijderen</button>";
            echo "</form>";
        }

        echo "<hr>";
    }
} else {
    echo "Geen berichten gevonden.";
}
?>

<br>

<form action="add_message.php" method="get">
<button type="submit">Nieuw bericht toevoegen</button>
</form>

<br>

<form action="logout.php" method="get">
<button type="submit">Uitloggen</button>
</form>

<?php
// Als de gebruiker een admin is, toon een link
if ($is_admin) {
    echo "<br><a href='forgot_password.php'>Wachtwoorden resetten</a>";
}
?>

</body>
</html>