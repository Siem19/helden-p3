<?php
require 'config.php';
session_start();

$error = '';

if(isset($_POST['inloggen'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users2 WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){

        $_SESSION['gebruiker'] = $username;

        header("Location: welkom.php");
        exit();

    } else {
        $error = "Onjuiste gebruikersnaam of wachtwoord";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Login</h2>

<form method="post">
Gebruikersnaam:<br>
<input type="text" name="username" required><br>

Wachtwoord:<br>
<input type="password" name="password" required><br><br>

<input type="submit" name="inloggen" value="Inloggen">
</form>

<p><?php echo $error; ?></p>

<a href="register.php">Registreren</a>

</body>
</html>