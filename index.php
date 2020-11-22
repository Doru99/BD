<?php

session_start();
$conn = sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS", array("Database"=>"GSM_OpreaStefanTeodor_333AA"));

?>

<html>
<head>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>
<?php

    if (isset($_POST['trimite'])) {
        $cnp=$_POST['CNP'];
        $pass=$_POST['pass'];

        $query = "SELECT Nume, Prenume, CNP, Sex, Strada, Oras FROM Angajati WHERE CNP = '$cnp'";
        $res = sqlsrv_query($conn, $query);
        if ($row = sqlsrv_fetch_array($res)) $_SESSION["log"] = 1;
        else $_SESSION["log"] = 0;

        list($_SESSION["Nume"], $_SESSION["Prenume"], $_SESSION["CNP"], $_SESSION["Sex"], $_SESSION["Strada"], $_SESSION["Oras"]) = $row;
    }
    if ($_SESSION["log"]) {
        $query = "SELECT CNP FROM Angajati WHERE Admin = 1";
        $res = sqlsrv_query($conn, $query);
        $row = sqlsrv_fetch_array($res);

        if ($_SESSION["CNP"] == $row['CNP']) $_SESSION["admin"] = 1;
        else $_SESSION["admin"] = 0;

?>
        <div class="topnav">
            <a class="active" href="index.php">Home</a>
            
            <?php
            if ($_SESSION["admin"]) {
            ?>
                <a href="adauga.php">Add Employ</a>
            <?php    
            }
            ?>

            <a href="login.php">Logout</a>
        </div>

        <?php

        if ($_SESSION["Sex"] == 'F') $file = "imagini/avatar_f.png";
        else $file = "imagini/avatar_m.png";

        ?>
    
        <div id="profile">
            <img class="avatar" src="<?php echo $file;?>">
            <span class="info"><?php echo $_SESSION["Nume"];?></span>
            <hr>
            <span class="info"><?php echo $_SESSION["Prenume"];?></span>
            <hr>
            <span class="info"><?php echo $_SESSION["Strada"];?></span>
            <hr>
            <span class="info"><?php echo $_SESSION["Oras"];?></span>
        </div>

        <?php
    }
    else {
        echo "Conectare esuata(Date incorecte)";
    }

?>

</body>
</html>