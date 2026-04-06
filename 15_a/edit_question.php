<?php
include 'config.php';

// Controleren of er een 'id' en 'update' actie in de request
if (isset($_GET['id']) && !isset($_POST['update'])) {
    $stmt = $conn->prepare("SELECT * FROM vraag_en_optie WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $question = $stmt->fetch();

    if ($question) {
        echo "<form action='edit_question.php' method='post'>
            <input type='hidden' name='id' value='" . $question['id'] . "'>
            Vraag: <input type='text' name='question' value='" . htmlspecialchars($question['vraag']) . "' required><br/>
            Antwoord 1: <input type='text' name='answer1' value='" . htmlspecialchars($question['antwoord1']) . "' required><br/>
            Antwoord 2: <input type='text' name='answer2' value='" . htmlspecialchars($question['antwoord2']) . "' required><br/>
            Antwoord 3: <input type='text' name='answer3' value='" . htmlspecialchars($question['antwoord3']) . "' required><br/>
            Antwoord 4: <input type='text' name='answer4' value='" . htmlspecialchars($question['antwoord4']) . "' required><br/>
            <input type='submit' name='update' value='Update Vraag'>
        </form>";
    } else {
        echo "Vraag niet gevonden.";
    }

} elseif (isset($_POST['update'])) {
    // Update de vraag in de database
    $stmt = $conn->prepare("UPDATE vraag_en_optie SET vraag = ?, antwoord1 = ?, antwoord2 = ?, antwoord3 = ?, antwoord4 = ? WHERE id = ?");
    $stmt->execute([
        $_POST['question'], 
        $_POST['answer1'], 
        $_POST['answer2'], 
        $_POST['answer3'], 
        $_POST['answer4'], 
        $_POST['id']
    ]);

    echo "Vraag bijgewerkt. <a href='manage_questions.php'>Terug naar beheer</a>";
}
?>