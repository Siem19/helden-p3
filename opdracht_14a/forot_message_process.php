<?php
// forgot_password_process.php
require_once 'config.php';

// Controleer of het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de gebruikersnaam en nieuw wachtwoord uit het formulier
    $username = $_POST['username'];
    $newPassword = $_POST['newPassword'];

    // Controleer of de gebruiker bestaat in de database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Gebruiker bestaat, update het wachtwoord in de database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password = ? WHERE username = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $hashedPassword, $username);
        $updateStmt->execute();

        // Controleer of het wachtwoord succesvol is bijgewerkt
        if ($updateStmt->affected_rows == 1) {
            // Wachtwoord is succesvol bijgewerkt
            session_start();
            $_SESSION['reset_username'] = $username;
            session_unset();
            session_destroy();
            echo "Wachtwoord is succesvol gewijzigd.<p><a href='index.php'>Opnieuw inloggen</a></p>";
        } else {
            // Er is iets misgegaan bij het bijwerken van het wachtwoord
            echo "Er is een fout opgetreden bij het wijzigen van het wachtwoord. Probeer het later opnieuw.";
        }
    } else {
        // Gebruiker bestaat niet
        echo "Gebruiker niet gevonden.";
    }
}

else {
    // Als het formulier niet is verzonden, stuur de gebruiker terug naar de forgot_password pagina
    header("Location: forgot_password.php");
    exit();
}
?>