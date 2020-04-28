<?php

if(!isset($_GET['section']))
{
    die(0);
}

if(empty($_GET['section']))
{
    $page = "dashboard";
}
else
{
    $page = $_GET['section'];
}

$location = '../dashboard/'.$page.'.php';

if(file_exists($location))
{
    include_once($location);
}

else
{
    echo 'No such page Exists!';
}

?>