<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cijferlijst</title>
<style>
table, td {
border: 1px solid black; /* Zet de randen van de tabel en cellen */
border-collapse: collapse; /* Verwijdert dubbele randen */
}

td {
padding: 8px; /* Voegt ruimte toe binnenin de cellen */
text-align: left; /* Tekstuitlijning in cellen */
}
</style>
</head>
<body>

<?php

// Probeer een verbinding te maken met de database en haal gegevens op
try {
// Maakt een nieuwe PDO-instance aan voor de connectie met de database
$db = new PDO("mysql:host=localhost;dbname=sem", "root", "");

// Bereidt de SQL-query voor om de leerlingnamen en cijfers op te halen
$query = $db->prepare("SELECT * FROM cijfer");

// Voert de voorbereide query uit
$query->execute();

// Haalt alle resultaten op als een associatieve array
$result = $query->fetchAll(PDO::FETCH_ASSOC);

// Begint met het bouwen van de HTML-tabel
echo "<table>";
// Voegt een rij toe met kopjes voor de kolommen
echo "<tr><th>Leerling</th><th>Cijfer</th></tr>";
// Loopt door elk resultaat en voegt een rij toe aan de tabel voor elke leerling
foreach ($result as $data) {
echo "<tr>";
echo "<td>" . htmlspecialchars($data['leerling']) . "</td>"; // Gebruik htmlspecialchars om XSS-aanvallen te voorkomen
echo "<td>" . htmlspecialchars($data['cijfer']) . "</td>";
echo "</tr>";
}

echo "</table>"; // Sluit de tabel af

} catch(PDOException $e) {
// Als er een fout optreedt, stop het script en toon de foutmelding
die("Error !: " . $e->getMessage());
}

?>

</body>
</html>