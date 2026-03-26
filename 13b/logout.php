<?php
session_start();       // Start of hervat de sessie
session_unset();       // Verwijder alle sessievariabelen
session_destroy();     // Vernietig de sessie

header("Location: login.php"); // Omleiden naar de inlogpagina
exit();
?>