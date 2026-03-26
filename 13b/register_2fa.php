<?php
require 'config.php';
require 'GoogleAuthenticator.php';
session_start();

if(!isset($_SESSION['temp_user_id'])){
    header("Location: login.php");
    exit();
}

$ga = new PHPGangsta_GoogleAuthenticator();
$error = '';

if(isset($_POST['verify'])){

    $code = $_POST['code'];

    if(!ctype_digit($code) || strlen($code) != 6){
        $error = "Code moet 6 cijfers zijn";
    } else {

        $stmt = $db->prepare("SELECT * FROM users2 WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['temp_user_id']);
        $stmt->execute();
        $user = $stmt->fetch();

        if($user && $ga->verifyCode($user['2fa_secret'], $code, 2)){

            session_regenerate_id(true);

            $_SESSION['gebruiker'] = $user['username'];
            unset($_SESSION['temp_user_id']);

            header("Location: welkom.php");
            exit();

        } else {
            $error = "Ongeldige code";
        }
    }
}
?>