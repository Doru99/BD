<?php

session_start();
$conn=sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS",array("Database"=>"GSM_OpreaStefanTeodor_333AA"));

?>

<html>
<head>
</head>
<body>
<?php

$cnp=$_POST['CNP'];
$pass=$_POST['pass'];

$query = "SELECT * FROM Angajati WHERE CNP='$cnp'";
$res = sqlsrv_query($conn, $query);
$row = sqlsrv_fetch_array($res);
echo "Salut, ".$row['Nume']." ".$row['Prenume']." !<br>";

$query = "SELECT CNP FROM Angajati WHERE Admin = 1";
$res = sqlsrv_query($conn, $query);
$row = sqlsrv_fetch_array($res);

if ($cnp==$row['CNP']) $_SESSION["admin"] = 1;
else $_SESSION["admin"] = 0;

if ($_SESSION["admin"]) {
?>
    <a href="adauga.php">Adauga angajati</a>
<?php    
}


?>


</body>
</html>