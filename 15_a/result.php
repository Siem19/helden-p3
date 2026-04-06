<?php
// Importeer de database connectie.
include 'config.php';

// Haal alle vragen op uit de database
$stmt = $conn->prepare("SELECT * FROM vraag_en_optie");
$stmt->execute();
$questions = $stmt->fetchAll();

// Itereer door elke vraag om de resultaten weer te geven
foreach ($questions as $question) {
    echo "<h2>" . htmlspecialchars($question['vraag']) . "</h2>";

    // Totaal aantal stemmen voor de huidige vraag initialiseren
    $totalVotes = 0;

    // Eerst berekenen we het totaal aantal stemmen voor deze vraag
    $stmt = $conn->prepare("SELECT SUM(votes) AS totalVotes FROM poll WHERE question_id = ?");
    $stmt->execute([$question['id']]);
    $totalResult = $stmt->fetch();
    if ($totalResult) {
        $totalVotes = $totalResult['totalVotes'];
    }

    // Toon elke antwoordoptie met het aantal stemmen en het percentage van het totaal
    for ($i = 1; $i <= 4; $i++) {
        $answer = $question["antwoord" . $i];
        if (!empty($answer)) { // Controleer of het antwoord bestaat
            // Haal het aantal stemmen op voor deze specifieke keuze
            $stmt = $conn->prepare("SELECT votes FROM poll WHERE question_id = ? AND choice = ?");
            $stmt->execute([$question['id'], $i]);
            $result = $stmt->fetch();
            $votes = $result ? $result['votes'] : 0;

            // Bereken het percentage van het totaal
            $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 2) : 0;
        }
    }


// Toon de resultaten
// htmlspecialchars() wordt gebruikt om HTML-tekens te ontsnappen om XSS-aanvallen te voorkomen.
echo htmlspecialchars($answer) . ": " . $votes . " stemmen (" . $percentage . "%)<br/>";
}
echo "<hr/>";
?>