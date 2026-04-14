<?php
/**
* OPDRACHT 3 - Browser en Besturingssysteem Detectie
*
* Dit script detecteert de browser en het besturingssysteem van de bezoeker,
* slaat deze informatie op in een database en toont dit aan de gebruiker.
*/
// ===== DATABASE CONFIGURATIE
// Instellingen voor verbinding met de MySQL database

$host = 'localhost';                 // Server adres
$db = 'browser';                      // Naam van de database
$user = 'root';                      // Database gebruikersnaam
$pass = '';                           // Database wachtwoord
$charset = 'utf8mb4';                  // Teken encoding

// ===== DSN EN OPTIES CONFIGUREREN
// Data Source Name (DSN) bevat alle noodzakelijke verbindingsinformatie
$dsn = "mysql:host=$host; dbname=$db;charset=$dharset";

// PDO opties voor betere foutafhandeling en veiliger gebruik
$options = [
PDO: : ATTR_ERRMODE            => PDO: : ERRMODE_EXCEPTION,      // Exceptions werpen op fouten
PDO: : ATTR_DEFAULT_FETCH_MODE => PDO: : FETCH_ASSOC,false,       // Data als associatieve array
PDO: : ATTR_EMULATE PREPARES   => false,                         // Echte prepared statements

];

// ===== DATABASEVERBINDING
try {
   // Maak verbinding met de database
   $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
   // als verbinding mislukt, toon foutmelding
   die("Databaseverbinding mislukt: " . htmlspecialchars($e->getMessage()));
}


/*

*Detecteert de browser en het besturingssysteem van de bezoeker
*
* Deze functie analyseert de User-Agent string uit de HTTP header
* om informatie over browser en OS te verkrijgen.
*
* @return array [$browser, $os, $version] - Array met browser naam, OS en versie
*/
function getBrowserAndOS() {
// Haal de User-Agent string op (info over browser/OS van de bezoeker)
$userAgent = $_SERVER['HTTP_USER_AGENT' ];

// Initialiseer variabelen met standaardwaarden
$browser = "Onbekend";
$os = "Onbekend";
$version = "Onbekende versie";

//===== BROWSER DETECTIE
// Controleer welke browser gebruikt wordt met behulp van regex (regular expressions)

if (preg_match('/Edg\/(\d+)/', $userAgent, $matches)) {
     // Microsoft Edge detectie
     $browser = "Microsoft Edge";
    $version = $matches[1]; // Haal versienummer uit de regex match

} elseif (preg_match('/Chrome\/(\d+)/', $userAgent, $matches)) {
    // Google Chrome detectie
    $browser = "Chrome";
    $version = $matches[1];

} elseif (preg_match('/Firefox\/(\d+)/', $userAgent, $matches)) {
   // Mozilla Firefox detectie
    $browser = "Firefox";
    $version = $matches[1];

} elseif (preg_match('/Opera|OPR\/(\d+)/', $userAgent, $matches) ) {
   // Opera detectie
   $browser = "Opera";
   $version = $matches[1] ?? ''; // Null coalescing operator: als geen match, leeg string

} elseif (preg_match('/Safari\/(\d+)/', $userAgent) && !preg_match('/Chrome/', $userAgent)) {
   // Safari detectie (maar niet Chrome, die ook Safari engine gebruikt)
   $browser = "Safari";
   preg_match('/Version\/(\d+)/', $userAgent, $matches);
   $version = $matches[1];

} elseif (preg_match('/MSIE (\d+)/', $userAgent, $matches) | | preg_match('/Trident\/ .*; rv:(\d+)/', $userAgent, $matches)) {
   // Internet Explorer detectie (oude en nieuwe versies)
   $browser = "Internet Explorer";
   $version = $matches[1];
}

// ===== BESIURINGSSYSIEEM DEIECIIE =====
// Controleer welk besturingssysteem gebruikt wordt

if (preg_match('/linux/i', $userAgent)) {
     // Linux detectie (case-insensitive)
    $os = "Linux";

} elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
     // Apple macOS detectie
    $os = "Mac";

} elseif (preg_match('/windows|win32/i', $userAgent)) {
   // Microsoft Windows detectie
   $os = "Windows":
}

// Retourneer de gedetecteerde waarden als array
return [$browser, $os, $version];
}

/** 
*Slaat een bezoek op in de database

* Deze functie voegt eem record in de 'visits' label in mel
* de browser en het besturingssysteem van de bezoeker.
*
* @param PDO $pdo - Database verbindingsobject
* @param string $browser - Naam van de browser
* @param string $os - Naam van het besturingssysteem
* @return void
*/
function insertVisit($pdo, $browser, $os) {
    // Prepared statement voor veilige database query (beschermt tegen SQL injection)
    $stmt = $pdo->prepare("INSERT INTO ]visits (browser, os) VALUES (?, ?)");

   // Voer prepared statement uit met de parameters
    $stmt->execute([$browser, $os]);
}

// ===== MAIN LOGICA =
// Detecteer browser en OS van de bezoeker
list($browser, $os, $version) = getBrowserAndoS();

// Sla het bezoek op in de database
insertVisit($pdo, $browser . " (Versie: " . $version . ")", $os);

// ===== TOON RESULTATEN AAN GEBRUIKER
// Geef feedback aan de bezoeker over hun browser en OS
echo "<h2>Browserdetectie Resultaten</h2>";
echo "<p><strong>Jouw browser :< /strong> " . htmlspecialchars($browser) . " (Versie: ". htmlspecialchars($version) . ")</p>";
echo "<p><strong>Jouw besturingssysteem :< /strong> " . htmlspecialchars($os) . "</p>";

?>