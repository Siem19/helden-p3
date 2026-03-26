<?php
// delete_message.php
require_once('config.php');

// Controleren of de gebruiker is ingelogd
session_start();
if (!isset($_SESSION['user_id'])) {
    // Als de gebruiker niet is ingelogd, stuur ze door naar de inlogpagina
    header("Location: login.php");
    exit();
}

// Controleren of het bericht behoort tot de ingelogde gebruiker of dat de gebruiker een admin is
$message_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

$sql = "SELECT * FROM gastboek WHERE id = $message_id";
$result = $conn->query($sql);

// Controleren of de bericht-id is opgegeven in de querystring
if (!isset($_GET['id'])) {
    // Als de bericht-id niet is opgegeven, stuur de gebruiker terug naar index.php
    header("Location: index.php");
    exit();
}
// Controleer of er een bericht met de opgegeven id is gevonden
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Controleren of de gebruiker eigenaar is van het bericht of een admin is
    if ($row['user_id'] == $user_id || $is_admin) {
        // Het bericht kan worden verwijderd
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Als bevestiging is gegeven, verwijder het bericht
            if (isset($_POST['confirm_delete'])) {
                $delete_sql = "DELETE FROM gastboek WHERE id = $message_id";
                if ($conn->query($delete_sql) === TRUE) {
                    // Als het bericht succesvol is verwijderd, stuur de gebruiker terug naar index.php
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Fout bij het verwijderen van het bericht: " . $conn->error;
                }
            }
        }
    } else {
        // Gebruiker heeft geen rechten om dit bericht te verwijderen
        echo "U heeft geen toestemming om dit bericht te verwijderen.";
    }
} else {
    echo"Bericht niet gevonden.";
}
?>

<!DOCTYPE html>
<html lang="nl">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bericht verwijderen</title>
</head>
<body>
    <h2>Bericht verwijderen</h2>
    <p>Weet u zeker dat u het volgende bericht wilt verwijderen?</p>
    <p><strong>Bericht:</strong> <?php echo $row['message']; ?></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$message_id"); ?>" method="post">
    <input type="submit" name="confirm_delete" value="Verwijderen">
    <button type="button" onclick="location.href='index.php';">Annuleren</button>
    </form>
</body>
</html>