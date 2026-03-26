

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Login</h2>

<f<?php
require 'config.php';
session_start();

$error = '';

if(isset($_POST['inloggen'])){

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users2 WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){

        // wachtwoord correct
        session_regenerate_id(true);

        if(!empty($user['2fa_secret'])){
            $_SESSION['temp_user_id'] = $user['id'];
            header("Location:welkom.php");
            exit();
        } else {
            $_SESSION['gebruiker'] = $user['username'];
            header("Location: welkom.php");
            exit();
        }

    } else {
        $error = "Onjuiste gebruikersnaam of wachtwoord";
    }
}
?>orm method="post">
    Gebruikersnaam:<br>
    <input type="text" name="username" required><br>

    Wachtwoord:<br>
    <input type="password" name="password" required><br>

    2FA Code:<br>
    <input type="text" name="code" required><br><br>

    <input type="submit" name="inloggen" value="Inloggen">
</form>

<p style="color:red;"><?php echo $error; ?></p>

<a href="register.php">Registreren</a>
<a href="qr.php">2FA instellen</a>

</body>
</html>