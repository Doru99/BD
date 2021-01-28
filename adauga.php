<?php

session_start();
$conn=sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS",array("Database"=>"GSM_OpreaStefanTeodor_333AA")); //conexiune

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

if (isset($_POST['laptop'])) {
    if ($_POST['reparator']) {
        $rep=$_POST['reparator'];
    }
    if ($_POST['producator']) {
        $prod=$_POST['producator'];
    }
    if ($_POST['proprietar']) {
        $prop=$_POST['proprietar'];
    }
    if ($_POST['data-primire']) {
        $data_pr=$_POST['data-primire'];
    }
    if ($_POST['data-reparatie']) {
        $data_rep=$_POST['data-reparatie'];
    }
    if ($_POST['ore']) {
        $ore=$_POST['ore'];
    }

    $query="SELECT AngajatID FROM Angajati WHERE Nume = '$rep'";
    $run = sqlsrv_query($conn, $query);
    $rep_id = sqlsrv_fetch_array($run);
    $rep_id = $rep_id["AngajatID"];

    $query="SELECT ProducatorID FROM Producatori WHERE Nume = 'Toshiba'";
    $run = sqlsrv_query($conn, $query);
    $prod_id = sqlsrv_fetch_array($run);
    $prod_id = $prod_id["ProducatorID"];

    $query="INSERT INTO Laptopuri(ReparatorID, ProducatorID, Nume, DataPrimire, DataReparare, OreReparatie)
    VALUES('$rep_id','$prod_id','$prop','$data_pr','$data_rep','$ore')"; //insert tabel 2
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
            <a href="index.php">Acasa</a>
            <a class="active" href="adauga.php">Adauga</a>
            <a href="remove.php">Gestiune Angajati</a>
            <a href="login.php">Logout</a>
        </div>
        <form action="adauga.php" method="POST">
            <span class="form-title">Adauga Angajat</span>
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

        <form action="adauga.php" method="POST">
            <span class="form-title">Adauga Laptop</span>
            <label for="reparator">Nume Reparator</label>
            <input type="text" name="reparator" placeholder="Reparator...">
            <br>
            
            <label for="producator">Producator</label>
            <input type="text" name="producator" placeholder="Producator...">
            <br>
            
            <label for="proprietar">Nume Proprietar</label>
            <input type="text" name="proprietar" placeholder="Proprietar..." required>
            <br>

            <label for="data-primire">Data Primire</label>
            <input type="date" name="data-primire" required>
            <br>
            
            <label for="date-reparatie">Data Reparatie</label>
            <input type="date" name="data-reparatie">
            <br>
            
            <label for="ore">Ore</label>
            <input type="text" name="ore" placeholder="Ore...">
            <br>
            
            <button type="submit" name="laptop" value="send">Adauga</button>
        </form>

    <?php    
    }
    ?>

</body>