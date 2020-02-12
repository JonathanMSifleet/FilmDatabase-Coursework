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

$genres = getData($connection, $movieID, "movie_genres", "genre_ID", "name");

echo "<br>Genre(s): ";

foreach ($genres as $curGenre) {
	echo $curGenre . ", ";
}

//////////////////////////

$languages = getData($connection, $movieID, "movie_languages", "iso_639","name");

echo "<br>Language(s): ";

foreach ($languages as $curLanguage) {
	echo $curLanguage . ", ";
}

//////////////////////////

$prodCountries = getData($connection, $movieID, "movie_countries","iso_3166", "name");

echo "<br>Production Country(s): ";

foreach ($prodCountries as $curCountry) {
	echo $curCountry . ", ";
}

///////////////////////////

$keywords = getData($connection, $movieID, "movie_keywords", "id","name");

echo "<br>Keywords: ";

foreach ($keywords as $curKeyword) {
	echo $curKeyword . ", ";
}

///////////////////////////

$prodCompanies = getData($connection, $movieID,"movie_companies", "id","name");

echo "<br>Production Companies: ";

foreach ($prodCompanies as $curCompany) {
	echo $curCompany . ", ";
}

///////////////////////////

echo "<br><br>Cast:<br>";
$castData = getCastData($connection, $movieID);

print_r($castData);

//////////////
/// display crew data

function getCastData($connection, $movieID) {

	$sql = "SELECT character_name, display_order FROM movie_cast ORDER BY display_order ASC";

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

function getData($connection, $movieID, $tableName, $joinOn, $dataToGet) {

	$joinTable = substr($tableName, 6);

	$sql = "SELECT $dataToGet FROM $tableName INNER JOIN $joinTable USING ($joinOn) WHERE movie_id=$movieID ORDER BY name ASC";

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