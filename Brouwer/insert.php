<?php
    // functie: formulier en database insert fiets
    // auteur: Vul hier je naam in

    echo "<h1>Insert Fiets</h1>";

    require_once('functions.php');
	 
    // Test of er op de insert-knop is gedrukt 
    if(isset($_POST) && isset($_POST['btn_ins'])){

        // test of insert gelukt is
        if(insertRecord($_POST) == true){
            echo "<script>alert('Fiets is toegevoegd')</script>";
        } else {
            echo '<script>alert("Fiets is NIET toegevoegd")</script>';
        }
    }
?>
<html>
    <body>
        <form method="post">

        <label for="naam">naam:</label>
        <input land="text" id="naam" name="naam" required><br>

        <label for="land">land:</label>
        <input land="text" id="land" name="land" required><br>

        

        <input land="submit" name="btn_ins" value="Insert">
        </form>
        
        <br><br>
        <a href='index.php'>Home</a>
    </body>
</html>
