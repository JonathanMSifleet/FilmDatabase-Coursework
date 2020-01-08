<?php

require_once "header.php";

$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

createDatabase($connection, $dbname);

createUserTable($connection);

createMovieTable();

function createDatabase($connection, $dbname)
{
    // build a statement to create a new database:
    $sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;
    // no data returned, we just test for true(success)/false(failure):
    if (mysqli_query($connection, $sql)) {
        echo "Database created successfully <br>";
    } else {
        die("Error creating database: " . mysqli_error($connection));
    }
    // connect to our database:
    mysqli_select_db($connection, $dbname);
}

function createUserTable($connection)
{
    $sql = "DROP TABLE IF EXISTS user";

    if (mysqli_query($connection, $sql)) {
        echo "Dropped existing table: user<br>";
    } else {
        die("Error checking for user table: " . mysqli_error($connection));
    }

    $sql = "CREATE TABLE user (id INT, username VARCHAR(20), firstname VARCHAR(16), surname VARCHAR(20), password VARCHAR(60), email VARCHAR(64))";

    if (mysqli_query($connection, $sql)) {
        echo "Table created successfully: user<br>";
    } else {
        die("Error creating table: " . mysqli_error($connection));
    }
}

/* function createMovieTable($connection) {
    $sql = "DROP TABLE IF EXISTS movie";

    if (mysqli_query($connection, $sql)) {
        echo "Dropped existing table: users<br>";
    } else {
        die("Error checking for user table: " . mysqli_error($connection));
    }

    $sql = "CREATE TABLE movie (VARCHAR)";
        echo "Table created successfully: movie<br>";
    } else {
        die("Error creating table: " . mysqli_error($connection));
    }

} */

?>