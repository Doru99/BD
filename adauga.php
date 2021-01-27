<?php

session_start();
$conn=sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS",array("Database"=>"GSM_OpreaStefanTeodor_333AA"));

if (isset($_POST['trimite'])) {
    if ($_POST['nume']) {
        $nume=$_POST['nume'];
    }
    if ($_POST['prenume']) {
        $prenume=$_POST['prenume'];
    }
    if ($_POST['cnp']) {
        $cnp=$_POST['cnp'];
    }
    if ($_POST['strada']) {
        $str=$_POST['strada'];
    }
    if ($_POST['oras']) {
        $oras=$_POST['oras'];
    }
    if ($_POST['judet']) {
        $jud=$_POST['judet'];
    }
    if ($_POST['sex']) {
        $sex=$_POST['sex'];
    }
    if ($_POST['data']) {
        $data=$_POST['data'];
    }
    if  ($_POST['salariu']) {
        $sal=$_POST['salariu'];
    }

    $query="INSERT INTO Angajati(Nume, Prenume, CNP, Strada, Oras, Judet, Sex, DataNasterii, Salariu)
    VALUES('$nume','$prenume','$cnp','$str','$oras','$jud','$sex','$data','$sal')"; //insert tabel 1
    sqlsrv_query($conn,$query);
}

?>

<html>
<head>
    <link rel="stylesheet" href="adauga.css">
</head>
<body>

<?php
    if ($_SESSION["admin"]) {
?>

        <div class="topnav">
            <a href=""><img class="logo" src="imagini/LOGOALB.png"></a>
            <a href="index.php">Home</a>
            <a class="active" href="adauga.php">Add Employ</a>
            <a href="remove.php">Remove Employ</a>
            <a href="login.php">Logout</a>
        </div>
        <form action="adauga.php" method="POST">
            
            <label for="nume">Last Name</label>
            <input type="text" name="nume" placeholder="Enter Last name" required>
            <br>
            
            <label for="prenume">First Name</label>
            <input type="text" name="prenume" placeholder="Enter First Name">
            <br>
            
            <label for="cnp">CNP</label>
            <input type="text" size=13 name="cnp" placeholder="Enter CNP" required>
            <br>
            
            <label for="strada">Street</label>
            <input type="text" name="strada" placeholder="Enter Street Name" required>
            <br>
            
            <label for="oras">City</label>
            <input type="text" name="oras" placeholder="Enter City" required>
            <br>
            
            <label for="judet">County</label>
            <input type="text" name="judet" placeholder="Enter County" required>
            <br>
            
            <label for="sex">Gender</label>
            <input type="text" name="sex" placeholder="Choose Gender" required>
            <br>
            
            <label for="data">Data Nasterii</label>
            <input type="date" name="data" max="2002-12-31" required>
            <br>
            
            <label for="salariu">Salariu</label>
            <input type="number" name="salariu" placeholder="Salary" min="0" max="99999" required>
            <br>
            <button type="submit" name="trimite" value="send">Adauga</button>
        </form>

    <?php    
    }
    ?>

</body>