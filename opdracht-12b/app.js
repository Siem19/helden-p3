// ===== DELETEBEVESTIGING FUNCTIE =====
/**
 * Functie die een bevestigingsdialoog toont wanneer een gebruiker op 'Verwijder' klikt
 * @returns {boolean} true als gebruiker bevestigt, false als gebruiker annuleert
 */
function confirmDelete() {
    return confirm("Weet je zeker dat je dit record wilt verwijderen? Deze actie kan niet ongedaan gemaakt worden.");
}

// ===== TABEL SORTERING FUNCTIE =====
/**
 * Sorteert een tabelkolom alfabetisch of numeriek, afhankelijk van de inhoud
 * - Klikken op dezelfde kolomkop wisselt tussen oplopend en aflopend
 * - Getallen worden correct numeriek gesorteerd
 * - Tekst wordt alfabetisch gesorteerd
 *
 * @param {number} column - De index van de kolom die gesorteerd moet worden (0-based)
 */
function sortTable(column) {
    // ===== STAP 1: INITIALISATIE =====
    // Selecteert de tabel en haalt de huidige sorteestatus op
    const table = document.querySelector("table");

    // Controleert of de tabel bestaat
    if (!table) {
        console.error("Fout: Geen tabel gevonden op deze pagina.");
        return;
    }

    const rows = Array.from(table.querySelectorAll("tbody tr")); // Haalt alle rijen op (excl. headers)
    const headers = table.querySelectorAll("th"); // Haalt alle kolomkoppen op

    // Controleer of de kolomindex geldig is
    if (column < 0 || column >= headers.length) {
        console.error("Fout: Ongeldige kolomindex.");
        return;
    }

    // ===== STAP 2: BEPAAL SORTEERRICHTING =====
// Controleert of deze kolom al gesorteerd is en bepaalt de volgende richting
const header = headers[column];
const isAscending = !header.dataset.sortAsc; // Toggle tussen true/false

// ===== STAP 3: VERWIJDER PIJLTJES VAN ANDERE KOLOMMEN =====
// Reset alle headers zodat maar een kolom een indicator heeft
headers.forEach((th, index) => {
    if (index !== column) {
        th.textContent = th.textContent.replace(/[\u2191\u2193]/g, ""); // Verwijder pijltjes
        th.dataset.sortAsc = ""; // Reset sorteerstatus
    }
});

// ===== STAP 4: SORTEER DE RIJEN =====
rows.sort((rowA, rowB) => {
    // Haalt de celtekst van beide rijen op
    let cellA = rowA.getElementsByTagName("TD")[column].textContent.trim();
    let cellB = rowB.getElementsByTagName("TD")[column].textContent.trim();

    // Probeert waarden als getallen te interpreteren
    const numA = parseFloat(cellA.replace(",", "."));
    const numB = parseFloat(cellB.replace(",", "."));

    // Bepaalt of beide waarden geldig getal zijn
    const isNumeric = !isNaN(numA) && !isNaN(numB);

    // Voert de vergelijking uit
    let comparison = 0;
    if (isNumeric) {
        // Numerieke vergelijking
        comparison = numA - numB;
    } else {
        // Alfabetische vergelijking
        comparison = cellA.localeCompare(cellB, undefined, { sensitivity: 'base' });
    }

    return isAscending ? comparison : -comparison;
});
// Alfabetische vergelijking (case-insensitive)
cellA = cellA.toLowerCase();
cellB = cellB.toLowerCase();
comparison = cellA.localeCompare(cellB, 'nl-NL');

// Bepaalt sorteerrichting (oplopend of aflopend)
return isAscending ? comparison : -comparison;

// ===== STAP 5: PLAATS GESORTEERDE RIJEN TERUG =====
// Plaatst alle gesorteerde rijen weer in de juiste volgorde
const tbody = table.querySelector("tbody") || table;
rows.forEach(row => {
    tbody.appendChild(row);
});

// ===== STAP 6: UPDATE KOLOMKOP MET VISUELE INDICATOR =====
// Voegt een pijltje toe aan de huidige kolomkop om sorteerrichting aan te geven
const arrow = isAscending ? " ↑" : " ↓"; // Pijl omhoog voor oplopend, omlaag voor aflopend
header.textContent += arrow;
header.dataset.sortAsc = isAscending; // Slaat sorteerstatus op voor volgende klik

// Wijzigt visuele stijl van de header om aan te geven dat deze gesorteerd is
headers.forEach((th, index) => {
    if (index === column) {
        th.style.backgroundColor = "#397d3d"; // Donkerder groen voor actieve kolom
    } else {
        th.style.backgroundColor = "#4CAF50"; // Standaard groen
    }
});
}