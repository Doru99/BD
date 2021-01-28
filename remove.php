<?php

session_start();
$conn = sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS", array("Database"=>"GSM_OpreaStefanTeodor_333AA")); //conexiune baza de date

?>

<html>
<head>
    <link rel="stylesheet" href="remove.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>

<?php
    if (isset($_POST["delete"])) {
        $cnp = $_POST["emp"];
        $query="DELETE FROM Angajati WHERE CNP = '$cnp'"; //delete tabel 1
        sqlsrv_query($conn, $query);
    }
    if (isset($_POST["creste10"])) {
        $query="UPDATE Angajati
        SET Salariu = 1.1 * Salariu"; //update tabel 1
        sqlsrv_query($conn, $query);
    }
    if ($_SESSION["admin"]) {
?>

        <div class="topnav">
            <a href=""><img class="logo" src="imagini/LOGOALB.png"></a>
            <a href="index.php">Acasa</a>
            <a href="adauga.php">Adauga</a>
            <a class="active" href="remove.php">Gestiune Angajati</a>
            <a href="login.php">Logout</a>
        </div>

        <table>
            <tr>
                <th colspan="2">Nume</th>
                <th>CNP</th>
                <th>Salariu</th>
                <th>Sterge</th>
            </tr>
        <?php
        $query = "SELECT Nume, Prenume, CNP, Salariu FROM Angajati";
        $res = sqlsrv_query($conn, $query);
        while($row = sqlsrv_fetch_array($res)) {
        ?>
            <tr>
                <td><?php echo $row["Nume"]?></td>
                <td><?php echo $row["Prenume"]?></td>
                <td><?php echo $row["CNP"]?></td>
                <td><?php echo $row["Salariu"]?></td>
                <td>
                    <form action="remove.php" method="POST">
                        <input type="hidden" name="emp" value="<?php echo $row["CNP"]?>">
                        <input type="submit" name="delete" value="">
                    </form>
                </td>
            </tr>
        <?php
        }
        ?>
        </table>

        <form action="remove.php" method="POST">
            <input type="submit" name="creste10" value="Creste salariile cu 10%">
        </form>
        
<?php
    }
?>
