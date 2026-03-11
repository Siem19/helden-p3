<?php
// ===== STAP 1: DATABASEVERBINDING INHALEN =====
include 'connect.php'; // Voeg de databaseverbinding toe

// ===== STAP 2: VALIDATIE VAN DE ID PARAMETER =====
// Controleert of de ID in de URL aanwezig is en of het een geldig getal is
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    // Als de ID ongeldig is, geef een duidelijke foutmelding
    die("<p class='error'><strong>Fout:</strong> Geen geldig record geselecteerd.</p>
         <a href='index.php'>Terug naar de cijferlijst</a>");
}

// ===== STAP 3: CONTROLEER OF RECORD BESTAAT VOORDAT HET WORDT VERWIJDERD =====
try {
    // Controleer eerst of het record met deze ID bestaat
    $checkQuery = $db->prepare("SELECT id FROM cijfersv2 WHERE id = :id");
    $checkQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $checkQuery->execute();

    // Als het record niet bestaat, geef een melding
    if ($checkQuery->rowCount() === 0) {
        die("<p class='error'><strong>Fout:</strong> Record met ID " . htmlspecialchars($id) . " niet gevonden.</p>
             <a href='index.php'>Terug naar de cijferlijst</a>");
    }

    // ===== STAP 4: VERWIJDER HET RECORD =====
    // Bereid de DELETE-query voor met een veilige parameter
    $deleteQuery = $db->prepare("DELETE FROM cijfersv2 WHERE id = :id");
    $deleteQuery->bindParam(':id', $id, PDO::PARAM_INT);

    // Voer de query uit
    if ($deleteQuery->execute()) {
        // Succesvol verwijderd - opslaan in sessie voor weergave op de volgende pagina
        session_start();
        $_SESSION['message'] = "Record succesvol verwijderd.";
        $_SESSION['messageType'] = "success";

        // Redirect terug naar de hoofdpagina
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    die("<p class='error'><strong>Databasefout:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         <a href='index.php'>Terug naar de cijferlijst</a>");
}
?>