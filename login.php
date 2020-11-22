<?php
session_start();
$_SESSION = array();
$conn=sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS",array("Database"=>"GSM_OpreaStefanTeodor_333AA"));

?>

<html>
    <head>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <form method="POST" action="index.php">
            <label for="CNP"><b>CNP</b></label>
            <input type="text" placeholder="Enter CNP" name="CNP">
            <label for="pass"><b>Password</b></label>
            <input type="text" placeholder="Enter password" name="pass">
            <button type="submit" name="trimite">Login</button>
    </body>
</html>