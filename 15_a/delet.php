<?php
include 'config.php';

if (isset($_GET['id'])) {
    // Vraag verwijderen
    $stmt = $conn->prepare("DELETE FROM vraag_en_optie WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    echo "Vraag verwijderd. <a href='manage_questions.php'>Terug naar beheer</a>";
} else {
    echo "Geen vraag gespecificeerd voor verwijdering.";
}
?>