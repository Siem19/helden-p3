<?php
// connect.php

// Maakt verbinding met de database binnen een try-catch blok.
// Dit is om eventuele fouten die optreden tijdens het verbindingsproces af te vangen.
try {
// Maakt een nieuwe PDO (PHP Data Objects) instance aan voor de connectie met de MySQL-database.
// Parameters zijn als volgt: DSN (Data Source Name), gebruikersnaam, wachtwoord.
// DSN bevat de databasetype, host (in dit geval localhost) en de databasenaam (`cijfers').
$db = new PDO("mysql:host=localhost;dbname=sem", "root", "");

// Stelt een attribuut van de databaseverbinding in.
// PDO :: ATTR_ERRMODE: Error reporting.
// PDO :: ERRMODE_EXCEPTION: Gooit een exception voor een databasefout. Dit is nuttig voor debugging
// omdat het gedetailleerde foutinformatie geeft en de uitvoering stopt bij fouten.
$db->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
} catch(PDOException $e) {
// Catch blok vangt de PDOException af die wordt gegooit als de verbinding mislukt.
// `die()' functie stopt de scriptuitvoering en toont de foutmelding.
// `$e->getMessage()' haalt de foutboodschap op van de exception, wat helpt bij het diagnosticeren van het probleem.
die("Verbindingsfout: " . $e->getMessage());
}
?>