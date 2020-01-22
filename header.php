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
        <title>MDBT</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="mystyle.css">
        </head>
    <h1>2CWK50: A Survey Website</h1>
    <nav>
_END;

session_start();



?>