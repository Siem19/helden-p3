<?php
include 'connect.php';

$search = $_GET['search'] ?? '';

try {
    $query = $db->prepare("SELECT * FROM cijfersv2 WHERE leerling LIKE :search ORDER BY leerling");
    $query->bindValue(':search', '%' . $search . '%');
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p class='error'><strong>Databasefout:</strong> " . htmlspecialchars($e->getMessage()) . "</p>");
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Zoeken</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<a href="index.php">Terug naar de hoofdpagina</a>

<form action="zoeken.php" method="get">
    <input type="text" name="search" placeholder="Zoek leerling" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Zoeken">
</form>

<?php if (!empty($result)): ?>
    <table>
        <thead>
            <tr>
                <th onclick="sortTable(0)">Leerling</th>
                <th onclick="sortTable(1)">Cijfer</th>
                <th onclick="sortTable(2)">Vak</th>
                <th onclick="sortTable(3)">Docent</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['leerling']); ?></td>
                    <td><?php echo htmlspecialchars($row['cijfer']); ?></td>
                    <td><?php echo htmlspecialchars($row['vak']); ?></td>
                    <td><?php echo htmlspecialchars($row['docent']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($search !== ''): ?>
    <p class="error"><strong>Geen resultaten gevonden</strong> voor "<?php echo htmlspecialchars($search); ?>"</p>
<?php endif; ?>

<script src="app.js"></script>
</body>
</html>