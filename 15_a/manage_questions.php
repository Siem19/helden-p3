<?php
// Dit bestand bevat de code voor het beheren van vragen in de poll.
// Het stelt de gebruiker in staat om nieuwe vragen toe te voegen, bestaande vragen te bewerken en te verwijderen.

include 'config.php'; // Zorg dat je verbinding maakt met de database

if (isset($_POST['addQuestion'])) {
    // Voeg nieuwe vraag toe
    $stmt = $conn->prepare("INSERT INTO vraag_en_optie (vraag, antwoord1, antwoord2, antwoord3, antwoord4) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['question'], $_POST['answer1'], $_POST['answer2'], $_POST['answer3'], $_POST['answer4']]);
    echo "Vraag toegevoegd.";
}
?>

<form action="manage_questions.php" method="post">
    Vraag: <input type="text" name="question" required><br/>
    Antwoord 1: <input type="text" name="answer1" required><br/>
    Antwoord 2: <input type="text" name="answer2" required><br/>
    Antwoord 3: <input type="text" name="answer3" required><br/>
    Antwoord 4: <input type="text" name="answer4" required><br/>
    <input type="submit" name="addQuestion" value="Voeg Vraag Toe">
</form>

<?php
$stmt = $conn->prepare("SELECT * FROM vraag_en_optie");
$stmt->execute();
$questions = $stmt->fetchAll();

foreach ($questions as $question) {
    echo "Vraag: " . htmlspecialchars($question['vraag']) . "<br/>";
    // Toon antwoorden hier indien nodig
    echo "<a href='edit_question.php?id=" . $question['id'] . "'>Bewerk</a> | ";
    echo "<a href='delete_question.php?id=" . $question['id'] . "'>Verwijder</a><br/><br/>";
}
?>
<a href="index.php">Index.php</a>