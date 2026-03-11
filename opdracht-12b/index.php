<?php
// index.php

include 'connect.php';
session_start();

// Toon sessieberichten
if (isset($_SESSION['message']) && isset($_SESSION['messageType'])) {
    $messageClass = ($_SESSION['messageType'] === 'success') ? 'success' : 'error';
    echo "<p class='" . htmlspecialchars($messageClass) . "'>" . htmlspecialchars($_SESSION['message']) . "</p>";

    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}

try {
    $query = $db->prepare("SELECT * FROM cijfersv2");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p class='error'><strong>Fout bij ophalen gegevens:</strong> " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Controleer of er data is
if (empty($result)) {
    echo "<p><strong>Geen gegevens gevonden in de database.</strong></p>";
} else {
    // ===== TABEL WEERGEVEN =====
    echo "<table>";
    echo "<thead>
            <tr>
                <th onclick='sortTable(0)'>Leerling</th>
                <th onclick='sortTable(1)'>Cijfer</th>
                <th onclick='sortTable(2)'>Vak</th>
                <th onclick='sortTable(3)'>Docent</th>
                <th>Acties</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($result as $data) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($data['leerling']) . "</td>";
        echo "<td>" . htmlspecialchars($data['cijfer']) . "</td>";
        echo "<td>" . htmlspecialchars($data['vak']) . "</td>";
        echo "<td>" . htmlspecialchars($data['docent']) . "</td>";
        echo "<td><a href='verwijder.php?id=" . htmlspecialchars($data['id']) . "' class='delete-link' onclick='return confirmDelete()'>Verwijderen</a></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
}
?>

<a href="zoeken.php">Zoeken</a>
<a href="invoeren.php">Invoeren</a>

<script src="app.js"></script>
<link rel="stylesheet" href="style.css">