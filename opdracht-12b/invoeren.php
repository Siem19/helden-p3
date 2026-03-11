<?php
// ===== STAP 0: SESSIES INITIALISEREN =====
// Start een sessie voor het opslaan van fouten en formulierdata
session_start();

// ===== STAP 1: CONTROLEER OF FORMULIER IS INGEDIEND =====
// Controleert of de gebruiker het formulier heeft gesubmit met POST-methode
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ===== STAP 2: DATABASEVERBINDING INHALEN =====
    // Voegt het bestand voor de databaseverbinding toe
    include 'connect.php';

    // ===== STAP 3: VALIDATIE VAN INGEVOERDE GEGEVENS =====
    // Maak een validatie-array aan voor foutmeldingen
    $errors = array();

    // Haal de formuliergegevens op en valideer/schoon ze
    // htmlspecialchars() zet speciale karakters om naar HTML-entiteiten (beschermt tegen XSS)
    // trim() verwijdert spaties aan het begin en einde
    $leerling = isset($_POST['leerling']) ? htmlspecialchars(trim($_POST['leerling'])) : '';
    if (empty($leerling)) {
        $errors['leerling'] = "Leerlingnaam is verplicht.";
    } elseif (strlen($leerling) > 100) {
        $errors['leerling'] = "Leerlingnaam mag maximaal 100 tekens zijn.";
    }

    // ===== CIJFER VALIDATIE =====
    // FILTER_VALIDATE_FLOAT accepteert zowel getallen als decimalen (bijv. 7.5)
    $cijfer = filter_input(INPUT_POST, 'cijfer', FILTER_VALIDATE_FLOAT);
    if ($cijfer === false || $cijfer === null) {
        $errors['cijfer'] = "Cijfer moet een getal zijn.";
    } elseif ($cijfer < 1 || $cijfer > 10) {
        $errors['cijfer'] = "Cijfer moet tussen 1 en 10 liggen.";
    }

    // ===== VAK VALIDATIE =====
$vak = isset($_POST['vak']) ? htmlspecialchars(trim($_POST['vak'])) : '';
if (empty($vak)) {
    $errors['vak'] = "Vak is verplicht.";
} elseif (strlen($vak) > 100) {
    $errors['vak'] = "Vak mag maximaal 100 tekens zijn.";
}

// ===== DOCENT VALIDATIE =====
$docent = isset($_POST['docent']) ? htmlspecialchars(trim($_POST['docent'])) : '';
if (empty($docent)) {
    $errors['docent'] = "Docentnaam is verplicht.";
} elseif (strlen($docent) > 100) {
    $errors['docent'] = "Docentnaam mag maximaal 100 tekens zijn.";
}

// ===== STAP 4: CONTROLEER OF ER FOUTEN ZIJN =====
// Haalt formuliergegevens op in sessie zodat deze kunnen worden getoond bij validatie
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['formData'] = [
        'leerling' => $leerling,
        'cijfer' => $cijfer,
        'vak' => $vak,
        'docent' => $docent
    ];

    // Redirect terug naar formulier met foutmeldingen
    header("Location: invoeren.php");
    exit();
}

// ===== STAP 5: GEGEVENS TOEVOEGEN AAN DATABASE MET FOUTAFHANDELING =====
try {
    // Bereid de SQL-query voor om de nieuwe gegevens in te voegen
    // De parameters (:leerling, :cijfer, :vak, :docent) beschermen tegen SQL-injectie
    $query = $db->prepare("INSERT INTO cijfersv2 (leerling, cijfer, vak, docent)
                           VALUES (:leerling, :cijfer, :vak, :docent)");

    // Bind de waarden aan de parameters
    // Dit is veiliger dan de waarden direct in de query in te voegen
    $query->bindParam(':leerling', $leerling);
    $query->bindParam(':cijfer', $cijfer, PDO::PARAM_STR); // Float als string
    $query->bindParam(':vak', $vak);
    $query->bindParam(':docent', $docent);

    // Voer de query uit
    if ($query->execute()) {
        // Succesvol ingevoegd - sla bericht op in sessie en redirect
        $_SESSION['message'] = "Het cijfer is succesvol toegevoegd.";
        $_SESSION['messageType'] = "success";

        // Verwijder formuliergegevens en fouten uit sessie
        unset($_SESSION['formData']);
        unset($_SESSION['errors']);

        // Redirect naar index zodat het succes-bericht wordt getoond
        header("Location: index.php");
        exit();
    }

    } catch (PDOException $e) {
    // Als de database een fout geeft, wordt dit afgevangen
    $_SESSION['errors'] = ["database" => "Databasefout: " . htmlspecialchars($e->getMessage())];
    header("Location: invoeren.php");
    exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nieuw Cijfer Toevoegen</title>
<link rel="stylesheet" href="style.css"> <!-- Link naar het externe CSS-bestand voor alle stijlen -->
<style>
/* Aanvullende stijlen voor formulier-validatie */
.form-group {
    margin-bottom: 15px;
}

.field-error {
    border-color: #d32f2f !important;
    background-color: #ffebee !important;
}

.error-message {
    color: #d32f2f;
    font-size: 12px;
    margin-top: 3px;
    display: block;
}

input.has-error {
    border: 2px solid #d32f2f;
}
</style>
</head>
<body>

<!-- ===== FORMULIER VOOR NIEUWE CIJFERS INVOEREN ===== -->
<h2>Nieuw Cijfer Invoeren</h2>

<!-- ===== FOUTEN WEERGEVEN ===== -->
<?php
// Controleert of er fouten zijn opgeslagen in de sessie
if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
    echo "<div class='error'>";
    echo "<strong>Validatiefouten gevonden:</strong><ul>";
    foreach ($_SESSION['errors'] as $field => $message) {
        echo "<li>" . htmlspecialchars($message) . "</li>";
    }
    echo "</ul></div>";
}
?>

<!-- Formulier dat gegevens moet POSTen naar dezezelfde pagina (invoeren.php) -->
<form action="invoeren.php" method="post" novalidate onsubmit="return validateForm()">

    <!-- LEERLING INVOERVELD -->
    <div class="form-group">
        <label for="leerling">Leerling:</label><br>
        <input type="text"
               id="leerling"
               name="leerling"
               maxlength="100"
               value="<?php echo isset($_SESSION['formData']['leerling']) ? htmlspecialchars($_SESSION['formData']['leerling']) : ''; ?>"
               class="<?php echo isset($_SESSION['errors']['leerling']) ? 'field-error' : ''; ?>"
               required
               placeholder="Voer leerlingnaam in">
        <?php
        if (isset($_SESSION['errors']['leerling'])) {
            echo "<span class='error-message'>" . htmlspecialchars($_SESSION['errors']['leerling']) . "</span>";
        }
        ?>
    </div>

    <!-- CIJFER INVOERVELD -->
<div class="form-group">
    <label for="cijfer">Cijfer:</label><br>
    <input type="number"
           id="cijfer"
           name="cijfer"
           min="1"
           max="10"
           step="0.1"
           value="<?php echo isset($_SESSION['formData']['cijfer']) && $_SESSION['formData']['cijfer'] !== false ? htmlspecialchars($_SESSION['formData']['cijfer']) : ''; ?>"
           class="<?php echo isset($_SESSION['errors']['cijfer']) ? 'field-error' : ''; ?>"
           required
           placeholder="Tussen 1 en 10">
    <?php
    if (isset($_SESSION['errors']['cijfer'])) {
        echo "<span class='error-message'>" . htmlspecialchars($_SESSION['errors']['cijfer']) . "</span>";
    }
    ?>
</div>

<!-- VAK INVOERVELD -->
<div class="form-group">
    <label for="vak">Vak:</label><br>
    <input type="text"
           id="vak"
           name="vak"
           maxlength="100"
           value="<?php echo isset($_SESSION['formData']['vak']) ? htmlspecialchars($_SESSION['formData']['vak']) : ''; ?>"
           class="<?php echo isset($_SESSION['errors']['vak']) ? 'field-error' : ''; ?>"
           required
           placeholder="Voer vaknaam in">
    <?php
    if (isset($_SESSION['errors']['vak'])) {
        echo "<span class='error-message'>" . htmlspecialchars($_SESSION['errors']['vak']) . "</span>";
    }
    ?>
</div>

<!-- DOCENT INVOERVELD -->
<div class="form-group">
    <label for="docent">Docent:</label><br>
    <input type="text"
           id="docent"
           name="docent"
           maxlength="100"
           value="<?php echo isset($_SESSION['formData']['docent']) ? htmlspecialchars($_SESSION['formData']['docent']) : ''; ?>"
           class="<?php echo isset($_SESSION['errors']['docent']) ? 'field-error' : ''; ?>"
           required
           placeholder="Voer docentnaam in">
    <?php
    if (isset($_SESSION['errors']['docent'])) {
        echo "<span class='error-message'>" . htmlspecialchars($_SESSION['errors']['docent']) . "</span>";
    }
    ?>
</div>

<!-- VERZENDKNOP -->
<input type="submit" value="Toevoegen">
</form>

<!-- NAVIGATIELINK -->
<p><a href="index.php">Terug naar de cijferlijst</a></p>

<script>
// ===== CLIENT-SIDE FORMULIER VALIDATIE =====
/**
 * Valideert het formulier aan de client-side voordat het naar de server wordt verzonden
 * Dit geeft snelle feedback zonder naar de server te hoeven gaan
 * @returns {boolean} true als het formulier geldig is, false anders
 */
function validateForm() {
    // Haalt alle inputvelden op
    const leerling = document.getElementById('leerling').value.trim();
    const cijfer = parseFloat(document.getElementById('cijfer').value);
    const vak = document.getElementById('vak').value.trim();
    const docent = document.getElementById('docent').value.trim();

    // Controleren of alle velden ingevuld zijn
    if (!leerling || isNaN(cijfer) || !vak || !docent) {
        alert("Alle velden zijn verplicht!");
        return false;
    }

    // Controleer of cijfer binnen de juiste range valt
    if (cijfer < 1 || cijfer > 10) {
        alert("Cijfer moet tussen 1 en 10 liggen!");
        return false;
    }

    return true; // Formulier is geldig
}

// Controleer de lengte van tekstvelden
if (leerling.length > 100 || vak.length > 100 || docent.length > 100) {
    alert("Tekstvelden mogen maximaal 100 karakters zijn!");
    return false;
}

return true; // Formulier is geldig

</script>

<!-- Verwijder sessiefoutgegevens na weergave -->
<?php
// Clear de error en form data uit de sessie zodat ze niet opnieuw worden getoond
unset($_SESSION['errors']);
unset($_SESSION['formData']);
?>

</body>
</html>