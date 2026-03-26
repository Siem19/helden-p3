<?php
require 'config.php';
session_start();
if(!isset($_SESSION['gebruiker']) || $_SESSION['gebruiker'] != 'admin') {
    die("Toegang geweigerd");
}

if(isset($_POST['username'], $_POST['newPassword'])) {
    $username = $_POST['username'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    try {
        $query = $db->prepare("UPDATE users2 SET password = :newPassword WHERE username = :username");
        $query->bindParam(':newPassword', $newPassword);
        $query->bindParam(':username', $username);
        $query->execute();
        echo "Wachtwoord voor $username gewijzigd. <a href='welkom.php'>Ga terug</a>";
    } catch(PDOException $e) {
        die("Fout: " . $e->getMessage());
    }
} else {
    echo "Vul alle velden in.";
}
?>