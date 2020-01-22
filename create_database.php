<?php

require_once "header.php";

$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

createDatabase($connection, $dbname);
createUserTable($connection);

$filename = "dumps/keywords.csv";
$dataDump = readDataDump($filename);

createKeywordTable($connection, $dataDump);

//createMovieTable($connection, $dataDump);

echo "<a href = home.php> Return to main page </a>";

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

    $sql = "CREATE TABLE user (id INT AUTO_INCREMENT, username VARCHAR(20), firstname VARCHAR(16), surname VARCHAR(20), password VARCHAR(60), email VARCHAR(64), PRIMARY KEY (id))";

    if (mysqli_query($connection, $sql)) {
        echo "Table created successfully: user<br>";
    } else {
        die("Error creating table: " . mysqli_error($connection));
    }
}

function readDataDump($filename)
{

    $tempFile = fopen($filename, "r") or die ("Unable to open");
    $arrayOfLines = array();

    $tempFile = fopen($filename, "r") or die ("Unable to open");

    $i = 0;
    while (($line = fgets($tempFile)) !== false) {
        $arrayOfLines[] = $line;
        $i++;
    }

    fclose($tempFile);

    return $arrayOfLines;

}

function createKeywordTable($connection, $dataDump)
{

    $sql = "DROP TABLE IF EXISTS keywords";

    if (mysqli_query($connection, $sql)) {
        echo "Dropped existing table: keywords<br>";
    } else {
        die("Error checking for user table: " . mysqli_error($connection));
    }

    $sql = "CREATE TABLE keywords (uniqueID VARCHAR(32), movie_ID MEDIUMINT,  id MEDIUMINT, name VARCHAR(64), PRIMARY KEY (uniqueID))";
    if (mysqli_query($connection, $sql)) {
        echo "Table created successfully: keyword<br>";
    } else {
        die("Error creating table: " . mysqli_error($connection));
    }

    //remove header from array:
    array_shift($dataDump);

    foreach ($dataDump as $line) {
        $lineAsArray = explode(",", $line);

        $movieID = $lineAsArray[0];

        $keywords = $lineAsArray[1] . "<br>";

        $arrayOfKeywords = explode("|", $keywords);

        foreach ($arrayOfKeywords as $keywordPairs) {

            $temp = explode("_", $keywordPairs);

            // get keyword ID:
            $keywordID = $temp[0];
            $keywordName = $temp[1];
            $temp = explode(": ", $keywordID);
            $keywordID = $temp[1];

            // get keyword name:
            $temp = explode(": '", $keywordName);
            $keywordName = $temp[1];
            $keywordName = substr($keywordName, 0, -1);
            //echo $keywordName . "<br>";

            $uniqueKID = md5($movieID . $keywordID . $keywordName);

            //insert keyword into table:

            $sql = "INSERT INTO keywords (uniqueID, movie_ID, id, name) VALUES ('$uniqueKID', $movieID, $keywordID, '$keywordName')";

            if (mysqli_query($connection, $sql)) {
            } else {
                die("Error inserting row: " . mysqli_error($connection));
            }

        }

    }

}

function createMovieTable($connection, $dataDump)
{
    $sql = "DROP TABLE IF EXISTS movie";

    if (mysqli_query($connection, $sql)) {
        echo "Dropped existing table: movies<br>";
    } else {
        die("Error checking for user table: " . mysqli_error($connection));
    }

    $sql = "CREATE TABLE movie (movie_ID MEDIUMINT, overview VARCHAR(4096), title VARCHAR(64),  release_date DATE, tmdb_ID VARCHAR(9), adult TINYINT(1), budget INT,  original_language VARCHAR(2), revenue BIGINT, PRIMARY KEY (movie_ID))";
    if (mysqli_query($connection, $sql)) {
        echo "Table created successfully: movie<br>";
    } else {
        die("Error creating table: " . mysqli_error($connection));
    }

    /* // remove first line:
    array_shift($dataDump);

    for ($i = 0; $i < 1; $i++) {

        $dataToInsert = explode(",", $dataDump[$i]);

        print_r($dataToInsert);

        for($j =0; $j < count($dataToInsert); $j++) {
            str_replace("|", ",", $dataToInsert[$j]);
        }

        $sql = "INSERT INTO movie (movie_ID, overview, title, release_date, tmdb_id, adult, budget, original_language, revenue) VALUES ($dataToInsert[0], $dataToInsert[1], $dataToInsert[2], $dataToInsert[3], $dataToInsert[4], $dataToInsert[5], $dataToInsert[6], $dataToInsert[7], $dataToInsert[8])";

        if (mysqli_query($connection, $sql)) {
        } else {
            die("Error inserting row: " . mysqli_error($connection));
        }

    } */

}

?>