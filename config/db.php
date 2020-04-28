<?php

    $serverName = "localhost";
    $serverUser = "root";
    $serverPass = "";
    $dbName     = "cms";

    $conn       = mysqli_connect($serverName, $serverUser, $serverPass, $dbName) or die("Err! Connection Failed!!" + mysqli_connect_error());

?>