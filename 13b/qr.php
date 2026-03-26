<?php
require 'config.php';
require 'GoogleAuthenticator.php';
session_start();

if(!isset($_SESSION['gebruiker'])){
    header("Location: login.php");
    exit();
}

$ga = new PHPGangsta_GoogleAuthenticator();
$username = $_SESSION['gebruiker'];
$error = '';

// haal user op
$stmt = $db->prepare("SELECT * FROM users2 WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch();

// al ingesteld?
if(!empty($user['2fa_secret'])){
    echo "2FA staat al aan!";
    exit();
}

// secret in session bewaren
if (!isset($_SESSION['2fa_secret_temp'])) {
    $_SESSION['2fa_secret_temp'] = $ga->createSecret();
}

$secret = $_SESSION['2fa_secret_temp'];
$qrCodeUrl = $ga->getQRCodeGoogleUrl($username, $secret);

if(isset($_POST['opslaan'])){

    $code = $_POST['code'];

    if($ga->verifyCode($secret, $code, 2)){

        $stmt = $db->prepare("UPDATE users2 SET 2fa_secret = :secret WHERE username = :username");
        $stmt->bindParam(':secret', $secret);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        unset($_SESSION['2fa_secret_temp']);

        echo "2FA ingesteld! <a href='welkom.php'>Ga verder</a>";
        exit();

    } else {
        $error = "Verkeerde code";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>2FA Instellen</title>
</head>
<body>

<h2>Scan deze QR-code met Google Authenticator</h2>
<img src="<?php echo $qrCodeUrl; ?>" alt="QR Code"><br><br>

<p>Of voer deze code handmatig in:</p>
<b><?php echo $secret; ?></b><br><br>

<form method="post">
    Voer de code uit je app in:<br>
    <input type="text" name="code" required><br><br>
    <input type="submit" name="opslaan" value="Bevestigen">
</form>

<p style="color:red;"><?php if($error) echo $error; ?></p>
<a href="login.php">Terug naar login</a>

</body>
</html>