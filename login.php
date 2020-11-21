<?php

$conn=sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS",array("Database"=>"GSM_OpreaStefanTeodor_333AA"));

?>

<html>
    <head>
    </head>
    <body>
        <form method="POST" action="index.php">
            <input type="text" placeholder="Enter CNP" name="CNP">
            <label for="CNP"><b>CNP</b></label>
            <input type="text" placeholder="Enter password" name="pass">
            <label for="pass"><b>Password</b></label>
            <button type="submit" name="trimite">Login</button>
    </body>
</html>