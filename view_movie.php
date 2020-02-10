<?php

require_once "header.php";

if (isset($_GET['movieID'])) {
	$movieID = $_GET['movieID'];
} else {
	$movieID = 19995;
}

$movieMetadata = getMovieData($connection, $movieID);

echo "<br>";

displayPoster($movieMetadata['poster_path']);

$releaseDate = date('d-m-Y', strtotime($movieMetadata['release_date']));
$revenue = '$' . $movieMetadata['revenue'];
$budget = '$' . $movieMetadata['budget'];
echo <<<_END
	<br>Title: {$movieMetadata['title']}
	<br>Rating: {$movieMetadata['rating']} ({$movieMetadata['votes']})
	<br>Popularity: {$movieMetadata['popularity']}
	<br>Tagline: {$movieMetadata['tagline']}
	<br>Overview: {$movieMetadata['overview']} 
	<br>Release date: $releaseDate
	<br>Revenue: {$revenue}
	<br>Budget: {$budget}
	<br>Runtime {$movieMetadata['runtime']} minutes
_END;

/////////////////////////

$genres = getData($connection, $movieID, "genres", "name");

echo "<br>Genre(s): ";

foreach ($genres as $curGenre) {
	echo $curGenre . ", ";
}

//////////////////////////

$languages = getData($connection, $movieID, "languages", "name");

echo "<br>Language(s): ";

foreach ($languages as $curLanguage) {
	echo $curLanguage . ", ";
}

//////////////////////////

$prodCountries = getData($connection, $movieID, "countries", "name");

echo "<br>Production Country(s): ";

foreach ($prodCountries as $curCountry) {
	echo $curCountry . ", ";
}

///////////////////////////

$keywords = getData($connection, $movieID, "keywords", "name");

echo "<br>Keywords: ";

foreach ($keywords as $curKeyword) {
	echo $curKeyword . ", ";
}

///////////////////////////

$prodCompanies = getData($connection, $movieID, "companies", "name");

echo "<br>Production Companies: ";

foreach ($prodCompanies as $curCompany) {
	echo $curCompany . ", ";
}

///////////////////////////

echo "<br><br>Cast:<br>";
$castData = getCastData($connection, $movieID, "cast");

foreach ($castData as $row) {
		echo $row['actor_name'];
		echo $row['character_name'];
		echo $row['gender'];
		echo "<br><br>";
}

/* echo "<br><br>Crew:<br>";
$crewData = getCrewData($connection, $movieID, "crew");

foreach ($crewData as $row) {
	print_r($row);
	echo "<br><br>";
} */

function getCastData($connection, $movieID) {

	$sql = "SELECT actor_name, character_name, display_order, gender, profile_path FROM cast WHERE movie_id=$movieID ORDER BY display_order ASC";

	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {

		return mysqli_fetch_all($result, MYSQLI_ASSOC);

	}
}

function getCrewData($connection, $movieID) {

	$sql = "SELECT department, gender, job, crew_name, profile_path FROM crew WHERE movie_id=$movieID";

	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {

		return mysqli_fetch_all($result, MYSQLI_ASSOC);

	}
}


function getMovieData($connection, $movieID) {

	$sql = "SELECT * FROM movie WHERE movie_id=$movieID";

	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {

		return mysqli_fetch_assoc($result);

	}

}

function getData($connection, $movieID, $tableName, $dataToGet) {
	$sql = "SELECT $dataToGet FROM $tableName WHERE movie_id=$movieID ORDER BY name ASC";

	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {

		$arrayOfData = array();

		while ($row = mysqli_fetch_array($result)) {
			$arrayOfData[] = $row[0];
		}

		return $arrayOfData;
	}
}

function displayPoster($posterPath) {
	$posterURL = "https://image.tmdb.org/t/p/original" . $posterPath;
	$imageData = base64_encode(file_get_contents($posterURL));
	echo '<img src="data:image/jpeg;base64,' . $imageData . '" height="auto" width="500px">';
}

?>