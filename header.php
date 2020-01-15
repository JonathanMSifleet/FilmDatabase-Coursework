<?php

require_once "helper.php";

////// MySQL credentials:
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'movie_database';
/////

$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// style sheet and header:
echo <<<_END
    <!DOCTYPE html>
    <html lang="en">
    <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/grid.css">
    <link rel="stylesheet" type="text/css" href="css/searchbar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Barlow&display=swap" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    </head>
    <h1>2CWK50: A Survey Website</h1>
    <nav>
_END;

session_start();



?>