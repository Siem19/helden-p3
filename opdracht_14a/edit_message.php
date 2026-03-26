<?php
// edit_message.php
require_once('config.php');

// Controleren of de gebruiker is ingelogd
session_start();
if (!isset($_SESSION['user_id'])) {
    // Als de gebruiker niet is ingelogd, stuur ze door naar de inlogpagina
    header("Location: login.php");
    exit();
}

// Controleren of de bericht-id is opgegeven in de querystring
if (!isset($_GET['id'])) {
    // Als de bericht-id niet is opgegeven, stuur de gebruiker terug naar index.php
    header("Location: index.php");
    exit();
}

// Controleren of het bericht behoort tot de ingelogde gebruiker of dat de gebruiker een admin is
$message_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

$sql = "SELECT * FROM gastboek WHERE id = $message_id";
$result = $conn->query($sql);

// Controleer of er een bericht met de opgegeven id is gevonden
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Controleren of de gebruiker eigenaar is van het bericht of een admin is
    if ($row['user_id'] == $user_id || $is_admin) {
        // Het bericht kan worden bewerkt
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Nieuwe inhoud van het bericht ophalen uit het formulier
            $new_message = $_POST['message'];

            // Query om het bericht bij te werken in de database
            $update_sql = "UPDATE gastboek SET message = '$new_message' WHERE id = $message_id";
            if ($conn->query($update_sql) === TRUE) {
                // Als het bericht succesvol is bijgewerkt, stuur de gebruiker terug naar index.php
                header("Location: index.php");
                exit();
            } else {
                echo "Fout bij het bijwerken van het bericht: " . $conn->error;
            }
        }
    } else {
        // Gebruiker heeft geen rechten om dit bericht te bewerken
        echo "U heeft geen toestemming om dit bericht te bewerken.";
    }
} else {
    // Bericht met opgegeven id niet gevonden
    echo "Bericht niet gevonden.";
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bericht bewerken</title>
</head>
<body>
    <h2>Bericht bewerken</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$message_id"); ?>" method="post">
    <label for="message">Nieuwe inhoud van het bericht:</label><br>
    <textarea id="message" name="message" rows="4" cols="50"><?php echo $row['message']; ?></textarea><br><br>

    <button type="submit">Bewerken opslaan</button>
    </form>
</body>
</html>