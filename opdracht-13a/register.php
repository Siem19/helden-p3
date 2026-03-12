<?php
require 'config.php';

$message = '';

if(isset($_POST['registreer'])) {

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users2 (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    if($stmt->execute()){
        $message = "Registratie gelukt!";
    } else {
        $message = "Fout bij registratie";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Registreren</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Registreren</h2>

<form method="post">
Gebruikersnaam:<br>
<input type="text" name="username" required><br>

Wachtwoord:<br>
<input type="password" name="password" required><br><br>

<input type="submit" name="registreer" value="Registreren">
</form>

<p><?php echo $message; ?></p>

<a href="login.php">Ga naar login</a>

</body>
</html>