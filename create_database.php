<?php

require_once "header.php";

$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

createDatabase($connection, $dbname);

createUserTable($connection);

//$dumpedFile = readDataDump($connection);

//var_dump($dumpedFile);

createMovieTable($connection);

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

function readDataDump($connection)
{

	$tempFile = fopen("movies_metadata.csv", "r") or die ("Unable to open");
	$arrayOfLines = array();

	$numLines = getNumLines($tempFile);

	$tempFile = fopen("movies_metadata.csv", "r") or die ("Unable to open");

	$i = 0;
	while (($line = fgets($tempFile)) !== false) {
		$arrayOfLines[] = explode(',', $line);
		$i++;
		//echo "Line " . $i . " of " . $numLines . "<br>";
	}

	fclose($tempFile);

	return $arrayOfLines;

}

function getNumLines($tempFile)
{
	$lineCount = 0;
	while (!feof($tempFile)) {
		$line = fgets($tempFile);
		$lineCount++;
	}

	return $lineCount;
}

function createMovieTable($connection) {
    $sql = "DROP TABLE IF EXISTS movie";

    if (mysqli_query($connection, $sql)) {
        echo "Dropped existing table: users<br>";
    } else {
        die("Error checking for user table: " . mysqli_error($connection));
    }

    $sql = "CREATE TABLE movie (adult TINYINT(1), budget INT, movie_ID MEDIUMINT, tmdb_ID VARCHAR(8), original_language VARCHAR(2), overview VARCHAR(4096), release_date DATE, revenue BIGINT, title VARCHAR(64), PRIMARY KEY (movie_ID))";
       	if (mysqli_query($connection, $sql)) {
	   echo "Table created successfully: movie<br>";
    } else {
        die("Error creating table: " . mysqli_error($connection));
    }

}

?>