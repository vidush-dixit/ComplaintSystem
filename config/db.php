<?php

//  For Development Purpose
//  $serverName = "localhost";
//  $serverUser = "root";
//  $serverPass = "";
//  $dbName     = "cms";

//  For Production Purpose - Remote Database(remotemysql.com)
    $serverName = "remotemysql.com";
    $serverUser = "HNVVvT1Qks";
    $serverPass = "tVkHHwobOe";
    $dbName     = "HNVVvT1Qks";

    $conn       = mysqli_connect($serverName, $serverUser, $serverPass, $dbName) or die("Err! Connection Failed!!" + mysqli_connect_error());

?>
