<?php

require_once "header.php";

set_time_limit(1200);

$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

createDatabase($connection, $dbname);
createUserTable($connection);

$filename = "dumps/movies.csv";
$dataDump = readDataDump($filename);
createMovieTable($connection, $dataDump);

// requires existence of movies table:
/* $filename = "dumps/keywords.csv";
$dataDump = readDataDump($filename);
createKeywordTable($connection, $dataDump);
tidyTable($connection); */

// requires existence of movies table:
$filename = "dumps/genres.csv";
$dataDump = readDataDump($filename);
createGenreTable($connection, $dataDump);
tidyTable($connection);

echo "<br><a href = home.php> Return to main page </a>";

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

function createMovieTable($connection, $dataDump)
{
	$sql = "DROP TABLE IF EXISTS movie";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: movie<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE movie (movie_ID MEDIUMINT, overview VARCHAR(4096), title VARCHAR(64),  release_date DATE, tmdb_ID VARCHAR(9), adult TINYINT(1), budget INT,  original_language VARCHAR(2), revenue BIGINT, PRIMARY KEY (movie_ID))";
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: movie<br>";
	} else {
		die("Error creating table: " . mysqli_error($connection));
	}
}

function createKeywordTable($connection, $dataDump)
{

	$sql = "DROP TABLE IF EXISTS keywords";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: keywords<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE keywords (uniqueID VARCHAR(32), movie_ID MEDIUMINT,  id MEDIUMINT, name VARCHAR(64), PRIMARY KEY (uniqueID), FOREIGN KEY (movie_ID) REFERENCES movie(movie_ID) ON DELETE CASCADE)";
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: keyword<br>";
	} else {
		die("Error creating table: " . mysqli_error($connection));
	}

	//remove header from array:
	array_shift($dataDump);

	$time_pre = microtime(true);

	foreach ($dataDump as $line) {

		$lineAsArray = explode(",", $line);
		$movieID = $lineAsArray[0];
		$keywords = $lineAsArray[1];
		$arrayOfKeywords = explode("|", $keywords);

		foreach ($arrayOfKeywords as $keywordPairs) {

			// get keyword ID:
			$temp = explode("_", $keywordPairs);
			$keywordID = $temp[0];
			$keywordName = $temp[1];
			$temp = explode(": ", $keywordID);
			$keywordID = $temp[1];

			// get keyword name:
			$temp = explode(": '", $keywordName);
			error_reporting(0);
			$keywordName = $temp[1];
			$keywordName = substr($keywordName, 0, -1);
			error_reporting(1);

			if (contains("'", $keywordName)) {
				$keywordName = str_replace("'", "", $keywordName);
			}

			$uniqueKID = md5($movieID . $keywordID . $keywordName);
			//insert keyword into table:
			$sql = "INSERT IGNORE INTO keywords (uniqueID, movie_ID, id, name) VALUES ('$uniqueKID', $movieID, $keywordID, '$keywordName')";

			if (mysqli_query($connection, $sql)) {
			} else {
				echo(mysqli_error($connection) . "<br>" . "movie ID: " . $movieID . ", keyword: " . $keywordName);
			}
		}
	}

	$time_post = microtime(true);

	// calculate difference between stop and start time:
	$timeTaken = $time_post - $time_pre;
	$timeTaken = $timeTaken * 1000;
	$timeTaken = floor($timeTaken);
	$timeTaken = $timeTaken / 1000;

	// display time taken to initiate database:
	echo "<br>Time taken: " . $timeTaken . " seconds<br>";

	echo "<br> Successfully populated keywords table";

}

function createGenreTable($connection, $dataDump) {

	$sql = "DROP TABLE IF EXISTS genres";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: genres<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE genres (uniqueID VARCHAR(32), movie_ID MEDIUMINT, genre_ID MEDIUMINT, name VARCHAR(64), PRIMARY KEY (uniqueID), FOREIGN KEY (movie_ID) REFERENCES movie(movie_ID) ON DELETE CASCADE)";
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: genres<br>";
	} else {
		die("Error creating table: " . mysqli_error($connection));
	}

	//remove header from array:
	array_shift($dataDump);

	$time_pre = microtime(true);

	foreach ($dataDump as $line) {

		$lineAsArray = explode(",", $line);
		$movieID = $lineAsArray[0];
		$genres = $lineAsArray[1];
		$arrayOfGenres = explode("|", $genres);

		foreach ($arrayOfGenres as $genrePairs) {

			// get keyword ID:
			$temp = explode("_", $genrePairs);
			$genreID = $temp[0];
			$genreName = $temp[1];
			$temp = explode(": ", $genreID);
			$genreID = $temp[1];

			// get keyword name:
			$temp = explode(": '", $genreName);
			error_reporting(0);
			$genreName = $temp[1];
			$genreName = substr($genreName, 0, -1);
			error_reporting(1);

			if (contains("'", $genreName)) {
				$genreName = str_replace("'", "", $genreName);
			}

			$uniqueKID = md5($movieID . $genreID . $genreName);
			//insert keyword into table:
			$sql = "INSERT IGNORE INTO genres (uniqueID, movie_ID, genre_ID, name) VALUES ('$uniqueKID', $movieID, $genreID, '$genreName')";

			if (mysqli_query($connection, $sql)) {
			} else {
				echo(mysqli_error($connection) . "<br>" . "movie ID: " . $movieID . ", genre: " . $genreName);
			}
		}
	}

	$time_post = microtime(true);

	// calculate difference between stop and start time:
	$timeTaken = $time_post - $time_pre;
	$timeTaken = $timeTaken * 1000;
	$timeTaken = floor($timeTaken);
	$timeTaken = $timeTaken / 1000;

	// display time taken to initiate database:
	echo "<br>Time taken: " . $timeTaken . " seconds<br>";

	echo "<br> Successfully populated genres table";
}

function tidyTable($connection)
{

	$sql = "DELETE FROM genres WHERE name = ''";
	if (mysqli_query($connection, $sql)) {
	} else {
		echo(mysqli_error($connection));
	}
}

?>