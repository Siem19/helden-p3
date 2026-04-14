<?php
/**
* OPDRACHT 2 - Browser en Besturingssysteem Detectie

* Dit script detecteert automatisch welke browser en besturingssysteem
* de bezoeker gebruikt door de User-Agent string te analyseren.
* Het toont vervolgens deze informatie aan de gebruiker.
*/

/**
* Detecteert de browser en het besturingssysteem van de bezoeker
*
* Deze functie analyseert de User-Agent string uit de HTTP header
* om informatie over de gebruikte browser en het besturingssysteem
* van de bezoeker te verkrijgen.
*
@return array [$browser, $os, $version] - Array met:
 *       - $browser: Naam van de gedetecteerde browser
 *       - $os: Naam van het besturingssysteem
 *       - $version: Versienummer van de browser
*/
function getBrowserAndos() {
// Haal de User-Agent string op uit de server variabelen
// Dit bevat informatie over browser, OS en andere details
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Initialiseer variabelen met standaardwaarden
$browser = "Onbekend";
$os = "Onbekend";
$version = "Onbekende versie"; // Standaard versie

// ===== BROWSER DETECTIE =====
// Controleer welke browser gebruikt wordt met regex (regular expressions)
// \d+ zoekt naar opeenvolgende cijfers (het versienummer)

if (preg_match('/Edg\/(\d+)/', $userAgent, $matches)) {
// Microsoft Edge detectie
// Het versienummer zit in $matches[1]
$browser = "Microsoft Edge";