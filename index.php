<?php

session_start();
$conn = sqlsrv_connect("DESKTOP-D8TQVLE\SQLEXPRESS", array("Database"=>"GSM_OpreaStefanTeodor_333AA")); //conectare

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

        if (isset($_POST["Salvare"])) {
            $pret = $_POST["pret"];
            $stoc = $_POST["stoc"];
            $piesa = $_POST["piesa"];
            $query = "UPDATE Componente
            SET Stoc = '$stoc', Pret = '$pret'
            WHERE Nume = '$piesa'"; //update tabel 2
            sqlsrv_query($conn, $query);
        }

        if (isset($_POST["Delete"])) {
            $nume_comp = $_POST["component_del"];
            $query="DELETE FROM Componente WHERE Nume = '$nume_comp'"; //delete tabel 2
            sqlsrv_query($conn, $query);
        }

?>
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

        if ($_SESSION["Sex"] == 'F') $file = "imagini/avatar_f.png";
        else $file = "imagini/avatar_m.png";

        ?>

        <div id="laptop_list">
            <?php
            $query = "SELECT L.Nume Proprietar, P.Nume Model, A.Nume, A.Prenume, L.OreReparatie FROM Laptopuri L
            JOIN Angajati A ON A.AngajatID = L.ReparatorID 
            JOIN Producatori P ON L.ProducatorID = P.ProducatorID"; //interogare simpla 2
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
                            WHERE L.Nume = '$proprietar'"; //interogare simpla 3
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
                    <?php
                    if ($_SESSION["admin"] == 1) {
                        ?>
                        <th>Modifica</th>
                        <th>Sterge</th>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                if (isset($_POST["Motherboard"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='MB'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Graphics"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='PV'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Power"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='S'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Cooler"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='C'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Display"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='M'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Ram"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='RAM'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                if (isset($_POST["Memory"]) or isset($_POST["All"])) {
                    $query="SELECT P.Nume Producator, C.Nume, C.Stoc, C.Pret FROM Componente C
                    JOIN TipComponente T ON C.TipID = T.TipID
                    JOIN Producatori P ON C.ProducatorID = P.ProducatorID
                    WHERE T.CodTip='Mem'
                    ORDER BY C.Stoc DESC"; //interogare simpla 4
                    $run = sqlsrv_query($conn, $query);
                    while ($row = sqlsrv_fetch_array($run)) {
                        ?>
                        <tr>
                            <td><?php echo $row["Producator"];?></td>
                            <td><?php echo $row["Nume"];?></td>
                            <td><?php echo $row["Stoc"];?></td>
                            <td><?php echo $row["Pret"];?></td>
                            <?php
                            if ($_SESSION["admin"] == 1) {
                            ?>
                            <td>
                                <form action="modifica.php" method="POST">
                                    <input type="hidden" name="component_m" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Modify" value="Modifica">
                                </form>
                            </td>
                            <td>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="component_del" value="<?php echo $row["Nume"]?>">
                                    <input type="submit" name="Delete" value="Sterge">
                                </form>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            </table>   
        </div>

        <div class="wrapper">
             <?php
                $sql="SELECT A.Nume, A.Prenume, COUNT(L.LaptopID) Laptopuri FROM Angajati A
                LEFT JOIN Laptopuri L ON A.AngajatID = L.ReparatorID
                GROUP BY A.Nume, A.Prenume
                HAVING SUM(L.OreReparatie) > 1"; //interogare simpla 5
                $run = sqlsrv_query($conn, $sql);
                ?>
                <table>
                    <tr>
                        <th>Nume</th>
                        <th>Prenume</th>
                        <th>Laptopuri reparate</th>
                    </tr>
                <?php
                while($row = sqlsrv_fetch_array($run)) {
                    ?>
                    <tr>
                    <td><?php echo $row["Nume"];?></td>
                    <td><?php echo $row["Prenume"];?></td>
                    <td><?php echo $row["Laptopuri"];?></td>
                    </tr>
                    <?php
                }
                ?>
                </table> 
        </div>

        <div class="wrapper">
            <?php
            $sql="SELECT TOP 1 P.Nume Producator, SUM(C.Stoc) Stoc FROM Producatori P
            JOIN Componente C ON P.ProducatorID = C.ProducatorID
            GROUP BY P.Nume
            ORDER BY SUM(C.Stoc) DESC"; //interogare simpla 6
            $run = sqlsrv_query($conn, $sql);
            ?>
            <table>
                <tr>
                    <th>Producator</th>
                    <th>Produse cumparate</th>
                </tr>
            <?php
            while($row = sqlsrv_fetch_array($run)) {
                ?>
                <tr>
                <td><?php echo $row["Producator"];?></td>
                <td><?php echo $row["Stoc"];?></td>
                </tr>
            <?php
            }
            ?>
            </table>
        </div>
        <div class="wrapper">
            <?php
            $sql="SELECT A.Nume, A.Prenume, SUM(L.OreReparatie) OreLucrate FROM Angajati A
            JOIN Laptopuri L ON L.ReparatorID = A.AngajatID
            GROUP BY A.Nume, A.Prenume
            HAVING SUM(L.OreReparatie) = (SELECT TOP 1 SUM(L1.OreReparatie) FROM Laptopuri L1
                                            GROUP BY L1.ReparatorID
                                            ORDER BY SUM(L1.OreReparatie) DESC)"; //interogare complexa 1
            $run = sqlsrv_query($conn, $sql);
            ?>
            <table>
                <tr>
                    <th>Nume</th>
                    <th>Prenume</th>
                    <th>Ore Lucrate</th>
                </tr>
            <?php
            while($row = sqlsrv_fetch_array($run)) {
                ?>
                <tr>
                <td><?php echo $row["Nume"];?></td>
                <td><?php echo $row["Prenume"];?></td>
                <td><?php echo $row["OreLucrate"];?></td>
                </tr>
            <?php
            }
            ?>
            </table>
        </div>
        <div class="wrapper">
            <?php
            $sql="SELECT TC.Nume 
            FROM TipComponente TC
            WHERE NOT EXISTS (SELECT * FROM Componente C WHERE C.TipID = TC.TipID)"; //interogare complexa 2
            $run = sqlsrv_query($conn, $sql);
            ?>
            <table>
                <tr>
                    <th>Componente</th>
                </tr>
            <?php
            while($row = sqlsrv_fetch_array($run)) {
                ?>
                <tr>
                <td><?php echo $row["Nume"];?></td>
                </tr>
            <?php
            }
            ?>
            </table>
        </div>
        <div class="wrapper">
            <form action="index.php" method="POST">
                <label for="pret">Pretul reparatiei:</label><input type="text" name="pret" placeholder="Pret reparatie..." required>
                <br>
                <input type="submit" name="trimite-pret">
            </form>
            <?php
            if (isset($_POST["trimite-pret"])) {
                $pret=$_POST["pret"];
            }
            else $pret = 0;
            $sql="SELECT P.Nume Model, L.Nume Proprietar FROM Laptopuri L
            JOIN Producatori P ON L.ProducatorID = P.ProducatorID
            WHERE L.LaptopID IN (SELECT LC.LaptopID FROM LaptopComponenta LC
                                JOIN Componente C ON LC.ComponentaID = C.ComponentaID
                                GROUP BY LC.LaptopID
                                HAVING SUM(C.Pret)>'$pret')";//variabila //interogare complexa 3
            $run = sqlsrv_query($conn, $sql);
            ?>
            <table>
                <tr>
                    <th>Proprietar</th>
                    <th>Model</th>
                </tr>
            <?php
            while($row = sqlsrv_fetch_array($run)) {
                ?>
                <tr>
                <td><?php echo $row["Proprietar"];?></td>
                <td><?php echo $row["Model"];?></td>
                </tr>
            <?php
            }
            ?>
            </table>
        </div>
        <div class="wrapper">
        <?php
            $sql="SELECT A.Nume, A.Prenume FROM Angajati A
            WHERE NOT EXISTS (SELECT L.LaptopID FROM Laptopuri L WHERE L.ReparatorID = A.AngajatID)";
            $run = sqlsrv_query($conn, $sql);
            ?>
            <table>
                <tr>
                    <th>Nume</th>
                    <th>Prenume</th>
                </tr>
            <?php
            while($row = sqlsrv_fetch_array($run)) {
                ?>
                <tr>
                <td><?php echo $row["Nume"];?></td>
                <td><?php echo $row["Prenume"];?></td>
                </tr>
            <?php
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