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
    echo $nume.$prenume.$cnp.$str.$oras.$jud.$sex.$data.$sal;

    $query="INSERT INTO Angajati(Nume, Prenume, CNP, Strada, Oras, Judet, Sex, DataNasterii, Salariu)
    VALUES('$nume','$prenume','$cnp','$str','$oras','$jud','$sex','$data','$sal')";
    sqlsrv_query($conn,$query);
}

?>

<html>
<head>
</head>
<body>

<?php
    if ($_SESSION["admin"]) {
?>
    <form action="adauga.php" method="POST">
        <input type="text" name="nume" placeholder="Enter Last name">
        <label for="nume">Last Name</label>
        <br>
        <input type="text" name="prenume" placeholder="Enter First Name">
        <label for="prenume">First Name</label>
        <br>
        <input type="text" size=13 name="cnp" placeholder="Enter CNP">
        <label for="cnp">CNP</label>
        <br>
        <input type="text" name="strada" placeholder="Enter Street Name">
        <label for="strada">Street</label>
        <br>
        <input type="text" name="oras" placeholder="Enter City">
        <label for="oras">City</label>
        <br>
        <input type="text" name="judet" placeholder="Enter County">
        <label for="judet">County</label>
        <br>
        <input type="text" name="sex" placeholder="Choose Gender">
        <label for="sex">Gender</label>
        <br>
        <input type="date" name="data">
        <label for="data">Data Nasterii</label>
        <br>
        <input type="number" name="salariu" min="0" max="99999">
        <label for="salariu">Salariu</label>
        <br>
        <button type="submit" name="trimite" value="send">Adauga</button>
    </form>

<?php
    }
?>

</body>