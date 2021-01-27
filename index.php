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

        $query = "SELECT A.Nume, A.Prenume, A.CNP, A.Sex, A.Strada, A.Oras, SUM(L.OreReparatie) Ore FROM Angajati A
        LEFT JOIN Laptopuri L ON L.ReparatorID = A.AngajatID
        WHERE CNP = '$cnp'
        GROUP BY A.Nume, A.Prenume, A.CNP, A.Sex, A.Strada, A.Oras"; //interogare simpla 1 (variabila)
        $res = sqlsrv_query($conn, $query);
        if ($row = sqlsrv_fetch_array($res)) $_SESSION["log"] = 1;
        else $_SESSION["log"] = 0;

        list($_SESSION["Nume"], $_SESSION["Prenume"], $_SESSION["CNP"], $_SESSION["Sex"], $_SESSION["Strada"], $_SESSION["Oras"], $_SESSION["Ore"]) = $row;
    }
    if ($_SESSION["log"]) {
        $_SESSION["admin"] = 0;
        $query = "SELECT CNP FROM Angajati WHERE Admin = 1";
        $res = sqlsrv_query($conn, $query);
        while($row = sqlsrv_fetch_array($res)) {
            if ($_SESSION["CNP"] == $row['CNP']) $_SESSION["admin"] = 1;
        }

?>
        <div class="topnav">
            <a href=""><img class="logo" src="imagini/LOGOALB.png"></a>
            <a class="active" href="index.php">Home</a>
            
            <?php
            if ($_SESSION["admin"]) {
            ?>
                <a href="adauga.php">Add Employ</a>
                <a href="remove.php">Remove Employ</a>
            <?php    
            }
            ?>

            <a href="login.php">Logout</a>
        </div>

        <?php

        if ($_SESSION["Sex"] == 'F') $file = "imagini/avatar_f.png";
        else $file = "imagini/avatar_m.png";

        ?>

        <div id="laptop_list">
            <?php
            $query = "SELECT L.Nume Proprietar, P.Nume Model, A.Nume, A.Prenume, L.OreReparatie FROM Laptopuri L
            JOIN Angajati A ON A.AngajatID = L.ReparatorID 
            JOIN Producatori P ON L.ProducatorID = P.ProducatorID"; //interogare simpla 3
            $run = sqlsrv_query($conn, $query);
            while ($row = sqlsrv_fetch_array($run)) {
            ?>
                <table class="laptop">
                    <tr>
                        <th>Proprietar: <?php echo $row["Proprietar"];?> </th>
                    </tr>
                    <tr>
                        <td>Model: <?php echo $row["Model"];?> </td>
                    </tr>
                    <tr>
                        <td>Reparator: <?php echo $row["Nume"]." ".$row["Prenume"];?> </td>
                    </tr>
                    <tr>
                        <td>Ore Reparatie: <?php
                        if ($row["OreReparatie"]) echo $row["OreReparatie"];
                        else echo "0";
                        ?> </td>
                    </tr>
                    <tr>
                        <td>Piese folosite: 
                        <?php
                            $proprietar = $row["Proprietar"];
                            $query = "SELECT C.Nume Piesa FROM Componente C
                            JOIN LaptopComponenta LC ON LC.ComponentaID = C.ComponentaID
                            JOIN Laptopuri L ON L.LaptopID = LC.LaptopID
                            WHERE L.Nume = '$proprietar'"; //interogare simpla 4
                            $run_comp = sqlsrv_query($conn, $query);
                            if ($row_comp = sqlsrv_fetch_array($run_comp)) {
                                echo $row_comp["Piesa"];
                                while ($row_comp = sqlsrv_fetch_array($run_comp)) {
                                    echo ", ".$row_comp["Piesa"];
                                }
                            }
                            else echo "-";
                        ?>
                        </td>
                    </tr>
                </table>
            <?php
            }
            ?>
        </div>

        <div id="profile">
            <img class="avatar" src="<?php echo $file;?>">
            <span class="info"><?php echo $_SESSION["Nume"];?></span>
            <hr>
            <span class="info"><?php echo $_SESSION["Prenume"];?></span>
            <hr>
            <span class="info"><?php echo $_SESSION["Strada"];?></span>
            <hr>
            <span class="info"><?php echo $_SESSION["Oras"];?></span>
            <hr>
            <span class="info"><?php 
                if ($_SESSION["Ore"])
                    echo $_SESSION["Ore"];
                else echo "0";?></span>
        </div>

        <div id="stoc_list">
            <form class="filter" action="index.php" method="POST">
                <input type="checkbox" name="Motherboard"><label for="Motherboard">Placi de baza</label>
                <br>
                <input type="checkbox" name="Graphics"><label for="Graphics">Placi video</label>
                <br>
                <input type="checkbox" name="Power"><label for="Power">Surse</label>
                <br>
                <input type="checkbox" name="Cooler"><label for="Cooler">Coolere</label>
                <br>
                <input type="checkbox" name="Display"><label for="Display">Monitoare</label>
                <br>
                <input type="checkbox" name="Ram"><label for="Ram">RAM</label>
                <br>
                <input type="checkbox" name="Memory"><label for="Memory">HDD</label>
                <br>
                <input type="checkbox" name="All" checked><label for="All">Toate</label>
                <br>
                <input type="submit" name="Filter">
            </form>
            <?php
            if (isset($_POST["Filter"])) {
                ?>
                <table>
                <tr>
                    <th>Producator</th>
                    <th>Nume</th>
                    <th>Stoc</th>
                    <th>Pret</th>
                </tr>
                <?php
                if (isset($_POST["Motherboard"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='MB'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Graphics"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='PV'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Power"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='S'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Cooler"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='C'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Display"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='M'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Ram"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='RAM'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Memory"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='Mem'
                    ORDER BY C.Stoc DESC"; //interogare simpla 2
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            </table>   
        </div>

        <?php
    }
    else {
        echo "Conectare esuata(Date incorecte)";
    }

?>

</body>
</html>