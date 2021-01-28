<?php

session_start();
$conn = sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS", array("Database"=>"GSM_OpreaStefanTeodor_333AA")); //conexiune baza de date

?>

<html>
<head>
    <link rel="stylesheet" href="modifica.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>

    <div class="topnav">
        <a href=""><img class="logo" src="imagini/LOGOALB.png"></a>
        <a class="active" href="index.php">Acasa</a>
        
        <?php
        if ($_SESSION["admin"]) {
        ?>
            <a href="adauga.php">Adauga</a>
            <a href="remove.php">Gestiune Angajati</a>
        <?php    
        }
        ?>

        <a href="login.php">Logout</a>
    </div>

    <?php
    if (isset($_POST["Modify"])) {
        $nume_comp = $_POST["component_m"];
    }
    ?>
    <form action="index.php" method="POST">
        <input type="hidden" name="piesa" value="<?php echo $nume_comp?>">
        <label for="pret">Pret nou:</labebl>
        <input type="text" name="pret">
        <br>
        <label for="stoc">Stoc nou:</label>
        <input type="text" name="stoc">
        <br>
        <input type="submit" name="Salvare" value="Salveaza">
    </form>

</body>