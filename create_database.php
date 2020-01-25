<?php

require_once "header.php";

set_time_limit(12000);

$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

createDatabase($connection, $dbname);

/* createUserTable($connection);
createMovieTable($connection);

$filename = "dumps/keywords.csv";
$dataDump = readDataDump($filename);
createKeywordTable($connection, $dataDump);

$filename = "dumps/genres.csv";
$dataDump = readDataDump($filename);
createGenreTable($connection, $dataDump);

$filename = "dumps/countries.csv";
$dataDump = readDataDump($filename);
createCountryTable($connection, $dataDump);

$filename = "dumps/companies.csv";
$dataDump = readDataDump($filename);
createCompaniesTable($connection, $dataDump); */

$filename = "dumps/spoken languages.csv";
$dataDump = readDataDump($filename);
createLanguagesTable($connection, $dataDump);

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

function createMovieTable($connection)
{
	$sql = "DROP TABLE IF EXISTS movie";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: movie<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE movie (movie_ID MEDIUMINT, overview VARCHAR(4096), title VARCHAR(64),  release_date DATE, imdb_ID VARCHAR(9), adult TINYINT(1), budget INT,  original_language VARCHAR(2), popularity DOUBLE, poster_path VARCHAR(32), revenue BIGINT, runtime SMALLINT, vote_average DOUBLE, vote_count MEDIUMINT, PRIMARY KEY (movie_ID))";
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

function createGenreTable($connection, $dataDump)
{

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
			error_reporting(1);

			if (contains("'", $genreName)) {
				$genreName = str_replace("'", "", $genreName);
			}

			if (contains("}", $genreName)) {
				$genreName = str_replace("}", "", $genreName);
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

function createCountryTable($connection, $dataDump)
{

	$sql = "DROP TABLE IF EXISTS genres";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: countries<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE genres (uniqueID VARCHAR(32), movie_ID MEDIUMINT, iso_3166 VARCHAR(2), name VARCHAR(64), PRIMARY KEY (uniqueID), FOREIGN KEY (movie_ID) REFERENCES movie(movie_ID) ON DELETE CASCADE)";
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
		$countries = $lineAsArray[1];
		$arrayOfCountries = explode("|", $countries);

		foreach ($arrayOfCountries as $countryPairs) {

			// get iso_3166 ID:
			$temp = explode("_", $countryPairs);
			$iso_3166 = $temp[0];
			$countryName = $temp[1];
			$temp = explode(": ", $iso_3166);
			$iso_3166 = $temp[1];

			$iso_3166 = trim($iso_3166, "'");

			echo $iso_3166 . "<br>";

			// get keyword name:
			$temp = explode(": '", $countryName);
			error_reporting(0);
			$countryName = $temp[1];
			error_reporting(1);

			if (contains("'", $countryName)) {
				$countryName = str_replace("'", "", $countryName);
			}

			if (contains("}", $countryName)) {
				$countryName = str_replace("}", "", $countryName);
			}

			$uniqueKID = md5($movieID . $iso_3166 . $countryName);
			//insert keyword into table:
			$sql = "INSERT IGNORE INTO countries (uniqueID, movie_ID, iso_3166, name) VALUES ('$uniqueKID', $movieID, '$iso_3166', '$countryName')";

			if (mysqli_query($connection, $sql)) {
			} else {
				echo(mysqli_error($connection) . "<br>" . "movie ID: " . $movieID . ", country: " . $countryName);
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

	echo "<br> Successfully populated countries table";
}

function createCompaniesTable($connection, $dataDump)
{

	$sql = "DROP TABLE IF EXISTS companies";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: companies<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE companies (uniqueID VARCHAR(32), movie_ID MEDIUMINT, companyName VARCHAR(64), id MEDIUMINT, PRIMARY KEY (uniqueID), FOREIGN KEY (movie_ID) REFERENCES movie(movie_ID) ON DELETE CASCADE)";
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: companies<br>";
	} else {
		die("Error creating table: " . mysqli_error($connection));
	}

	//remove header from array:
	array_shift($dataDump);

	$time_pre = microtime(true);

	foreach ($dataDump as $line) {

		$lineAsArray = explode(",", $line);
		$movieID = $lineAsArray[0];
		$companies = $lineAsArray[1];
		$arrayOfCompanies = explode("|", $companies);

		foreach ($arrayOfCompanies as $companyPairs) {

			// get company name:
			$temp = explode("_", $companyPairs);
			$companyName = $temp[0];
			$companyID = $temp[1];
			$temp = explode(": ", $companyName);
			$companyName = $temp[1];

			$companyName = trim($companyName, "'");

			if (contains("'", $companyName)) {
				$companyName = str_replace("'", "", $companyName);
			}

			// get company id:
			$temp = explode(": ", $companyID);
			error_reporting(0);
			$companyID = $temp[1];
			error_reporting(1);

			if (contains("}", $companyID)) {
				$companyID = str_replace("}", "", $companyID);
			}

			/* if($movieID == 404) {
				echo "company name: " . $companyName . ", ID:" . $companyID."<br>";
			} */


			$uniqueKID = md5($movieID . $companyName . $companyID);
			//insert keyword into table:
			$sql = "INSERT IGNORE INTO companies (uniqueID, movie_ID, companyName, id) VALUES ('$uniqueKID', $movieID, '$companyName', $companyID)";

			if (mysqli_query($connection, $sql)) {
			} else {
				echo(mysqli_error($connection) . "<br>" . "movie ID: " . $movieID . ", id: " . $companyID);
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

	echo "<br> Successfully populated companies table";

}

function createLanguagesTable($connection, $dataDump)
{
	$sql = "DROP TABLE IF EXISTS languages";

	if (mysqli_query($connection, $sql)) {
		echo "Dropped existing table: languages<br>";
	} else {
		die("Error checking for user table: " . mysqli_error($connection));
	}

	$sql = "CREATE TABLE languages (uniqueID VARCHAR(32), movie_ID MEDIUMINT, iso_639 VARCHAR(2), name VARCHAR(64), PRIMARY KEY (uniqueID), FOREIGN KEY (movie_ID) REFERENCES movie(movie_ID) ON DELETE CASCADE)";
	if (mysqli_query($connection, $sql)) {
		echo "Table created successfully: languages<br>";
	} else {
		die("Error creating table: " . mysqli_error($connection));
	}

	//remove header from array:
	array_shift($dataDump);

	$time_pre = microtime(true);

	foreach ($dataDump as $line) {

		$lineAsArray = explode(",", $line);
		$movieID = $lineAsArray[0];
		$languages = $lineAsArray[1];
		$arrayOfLanguages = explode("|", $languages);

		foreach ($arrayOfLanguages as $languagePairs) {

			// get iso_639:
			$temp = explode("_", $languagePairs);
			$iso_639 = $temp[0];
			$languageName = $temp[1];
			$temp = explode(": ", $iso_639);
			$iso_639 = $temp[1];
			$iso_639 = trim($iso_639, "'");

			// get keyword name:
			$temp = explode(": '", $languageName);
			error_reporting(0);
			$languageName = $temp[1];
			error_reporting(1);

			if (contains("'", $languageName)) {
				$languageName = str_replace("'", "", $languageName);
			}

			if (contains("}", $languageName)) {
				$languageName = str_replace("}", "", $languageName);
			}

			$uniqueKID = md5($movieID . $iso_639 . $languageName);
			//insert keyword into table:
			$sql = "INSERT IGNORE INTO languages (uniqueID, movie_ID, iso_639, name) VALUES ('$uniqueKID', $movieID, '$iso_639', '$languageName')";

			if (mysqli_query($connection, $sql)) {
			} else {
				echo(mysqli_error($connection) . "<br>" . "movie ID: " . $movieID . ", language: " . $languageName);
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

	echo "<br> Successfully populated languages table";

}


?>