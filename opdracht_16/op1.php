<?php
/**

* OPDRACHT 1 - Willekeurig Wachtwoord Generator
*
* Dit script genereert een willekeurig wachtwoord van een opgegeven lengte.
* Het wachtwoord bevat letters (groot en klein), cijfers en kan eventueel
* speciale karakters bevatten.
*/

// ===== FUNCTIE AANROEPING =====
// Roep de functie aan met een gewenste wachtwoord lengte
$gegenereerd_wachtwoord = generatePassword(10);
echo $gegenereerd_wachtwoord;

/** 

* Genereert een willekeurig wachtwoord van de opgegeven lengte
*

* Deze functie maakt een veilig wachtwoord aan door willekeurig
* karakters te selecteren uit een verzameling van kleine letters,
* hoofdletters en cijfers.

*

@param int $length - De gewenste lengte van het wachtwoord
@return string - Het gegenereerde wachtwoord
*/
function generatePassword($length) {
// ===== KARAKTERS VOOR WACHTWOORD =====
// Dit zijn alle mogelijke karakters die in het wachtwoord kunnen voorkomen
// - Kleine letters: a-z
// - Hoofdletters: A-Z
// - Cijfers: 0-9
$alphabet = "abedefghijklmmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

// Validatie: Check of de lengte geldig is
if ($length <= 0) {
return "Fout: Wachtwoord lengte moet groter zijn dan 0";

}

// Initialiseer lege string voor het wachtwoord
$password = "";

// =====LOOP: BOU WW KARAKTER VOOR KARAKTER
// Deze loop herhaalt zich zoveel keer als de opgegeven lengte
for ($i = 0; $i < $length; $i++) {

// ===== SELECTEER WILLEKEURIG KARAKTER ====
// rand(0, X) genereert een willekeurig getal tussen 0 en X
// strlen() geeft de lengte van de string
// strlen($alphabet) - 1 = nummer van laatste karakter in de string
// Voorbeeld: Talphabet heeft 62 karakters (0-61), dus rand(0, 61)
$randomIndex = rand(0, strlen($alphabet) - 1);

// ==== VOEG KARAKTER TOE AAN WACHTWOORD
// $alphabet[$randomIndex] haalt het karakter op de willekeurige positie
// .= voegt het karakter toe aan het einde van $password
$password .= $alphabet[$randomIndex];
}

// ===== RESULTAAT TONEN EN TERUGGEVEN =====
// Toon het gegenereerde wachtwoord in een mooie HTML-structuur
echo "<h2>Willekeurig Wachtwoord Generator</h2>";
echo "<p>";
echo "<strong>Wachtwoord lengte: </strong> " . htmlspecialchars($length) . " tekens<br>";
echo "<strong>Gegenereerd wachtwoord :</strong> <code style='background-color: #f0f0f0; padding: 5px; font-weight: bold;'>"
     . htmlspecialchars($password) . "</code>";
echo "</p>";

// Retourneer het gegenereerde wachtwoord
return $password;
}