<?php
/**

* OPDRACHT 4 - Cijfers Beheer Applicatie

* Dit script haalt leerlinggegevens en hun cijfers uit een MySQL database,
* berekent statistieken (gemiddelde, hoogste, laagste) en toont alles in een tabel.
*/

// DATABASE CONFIGURATIE
// Instellingen voor verbinding met de MySQL database
$servername = "localhost"; // Server adres
$username = "root";          // Database gebruikersnaam
$password = "";            // Database wachtwoord
$dbname = "browser";           // Naam van de database


// DATABASEVERBINDING
try {

   // Maak verbinding met de database met PDO (PHP Data Objects)
   $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
   // Zet foutafhandeling op exception-modus voor betere error handling
   $conn->setAttribute(PDO :: ATTR_ERRMODE, PDO: : ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  // Als verbinding mislukt, toon foutmelding
  echo "Databaseverbinding mislukt: " . htmlspecialchars($e->getMessage());
  exit; // Stop uitvoering script
}

// ===== GEGEVENS OPHALEN
// SQL-query om alle leerlingen en hun cijfers uit de database te halen
$query = "SELECT * FROM cijfers";

try {
    // Voer query uit en haal alle resultaten op als associatieve array
    $stmt = $conn->query($query);
    $result = $stmt->tetchAll(PDO :: FETCH_ASSOC);

// Variabelen voor het berekenen van statistieken
$totaalCijfer = 0;               // Som van alle cijfers
$hoogsteCijfer = 0;            // Hoogste cijfer
$laagsteCijfer = PHP_INT_MAX;         // Laagste cijfer (begin met max waarde)
$aantalCijfers = 0;           // Aantal leerlingen

// ===== TABEL WEERGEVEN
// Controleer of er resultaten uit de database zijn
if ($result && count($result) > 0) {
// Begin HTML tabel met borders
echo "<table border='1' cellpadding='8' cellspacing='0'>";
echo "<tr><th>ID</th><th>Leerling</th><th>Cijfer</th></tr>";

// ===== LOOP DOOR ALLE LEERLINGEN
// Voor elke leerling: toon gegevens en update statistieken
foreach ($result as $row)
$cijfer = (float)$row['cijfer']; // Zet cijfer om naar getal

// Update statistieken
$totaalCijfer += $cijfer;                              // Tel cijfer op
$hoogsteCijfer = max($hoogsteCijfer, $cijfer);          // Controleer of dit het hoogste is
$laagsteCijfer = min($laagsteCijfer, $cijfer);          // Controleer of dit het laagste is
$aantalCijfers++;                                        // Verhoog teller

// Toon rij in tabel met veilig gemaakte gegevens
echo "<tr>";
echo "<td>" . htmlspecialchars($row['id']) . "</td>";
echo "<td>" . htmlspecialchars($row['leerling']) . "</td>";
echo "<td>" . htmlspecialchars($cijfer) . "</td>";
echo "</tr>";

echo "</table>";

// ====STATISTISCHE GEGEVENS TONEN
// Bereken het gemiddelde cijfer
$gemiddeldeCijfer = $totaalCijfer / $aantalCijfers;

// Toon alle statistische informatie
echo "<h3>Statistieken :< /h3>";
echo "<p><strong>Gemiddelde cijfer :< /strong> " . round($gemiddeldeCijfer, 1) . "</p>";
echo "<p><strong>Hoogste cijfer :< /strong> " . $hoogsteCijfer . "</p>";
echo "<p><strong>Laagste cijfer :< /strong> " . $laagsteCijfer . "</p>";
echo "<p><strong>Som van alle cijfers :< /strong> " . $totaalCijfer . "</p>";
echo "<p><strong>Aantal leerlingen :< /strong> " . $aantalCijfers . "</p>";

} else {
// Toon bericht als geen gegevens gevonden zijn
echo "Geen leerlingen of cijfers gevonden in de database.";
}
} catch(PDOException $e) {
// Toon foutmelding als query mislukt
echo "Query mislukt: " . htmlspecialchars($e->getMessage());
}

// ===== VERBINDING SLUITEN =====
// Verbreek de databaseverbinding (PDO doet dit automatisch, maar dit maakt het expliciet)
$conn = null;
?>