<?php

require_once "header.php";

if (isset($_POST['title'])) {

	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$Title = $_POST['title'];

	$query = "SELECT title, movie_id FROM movie WHERE title = '$Title' ";
	$result = mysqli_query($connection, $query);

	while ($row = mysqli_fetch_array($result)) {

		$movieID = $row['movie_id'];

	}

} else if (isset($_GET['movieID'])) {

	$movieID = $_GET['movieID'];
} else {
	$movieID = 19995;
}

$movieMetadata = getMovieData($connection, $movieID);

echo "<br>";

displayPicture($movieMetadata['poster_path'], "moviePoster");

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

$languages = getData($connection, $movieID, "movie_languages", "iso_639", "name");

echo "<br>Language(s): ";

foreach ($languages as $curLanguage) {
	echo $curLanguage . ", ";
}

//////////////////////////

$prodCountries = getData($connection, $movieID, "movie_countries", "iso_3166", "name");

echo "<br>Production Country(s): ";

foreach ($prodCountries as $curCountry) {
	echo $curCountry . ", ";
}

///////////////////////////

$keywords = getData($connection, $movieID, "movie_keywords", "id", "name");

echo "<br>Keywords: ";

foreach ($keywords as $curKeyword) {
	echo $curKeyword . ", ";
}

///////////////////////////

$prodCompanies = getData($connection, $movieID, "movie_companies", "id", "name");

echo "<br>Production Companies: ";

foreach ($prodCompanies as $curCompany) {
	echo $curCompany . ", ";
}

///////////////////////////

echo "<br><br>Cast:<br>";
$castData = getCastData($connection, $movieID);

echo "<div class='container-fluid'>";
echo "<div class='row justify-content-center'>";

foreach ($castData as $castMember) {
	$characterName = $castMember['character_name'];
	$creditName = $castMember['credit_name'];
	$profilePath = $castMember['profile_path'];

	$hasPicture = false;

	if ($profilePath != "" || $profilePath != null) {
		$posterURL = "https://image.tmdb.org/t/p/original" . $profilePath;
		$imageData = base64_encode(file_get_contents($posterURL));
		$hasPicture = true;
	}

	echo <<<_END
	<div>
    	<div class="card">
        	<div class="card-body">
_END;

	if ($hasPicture) {
		echo '<img class="card-img-top" src="data:image/jpeg;base64,' . $imageData . '" id ="moviePoster">';
	}

	echo <<<_END
                <h4 class="card-title">$creditName</h4>
                <p class="card-text">$characterName</p>
            </div>
		</div>
	</div>
_END;
}
echo "</div";
echo "</div";

///////////////////


echo "<br><br>Crew::<br>";
$crewData = getCrewData($connection, $movieID);

echo "<div class='container-fluid'>";
echo "<div class='row justify-content-center'>";

foreach ($crewData as $crewMember) {
	$department = $crewMember['department'];
	$job = $crewMember['job'];
	$creditName = $crewMember['credit_name'];
	$profilePath = $crewMember['profile_path'];

	echo <<<_END
	<div>
    	<div class="card">
        	<div class="card-body">
_END;

	if ($profilePath != "" || $profilePath != null) {
		displayPicture($profilePath, "moviePoster");
	}

	echo <<<_END
                <h4 class="card-title">$creditName</h4>
                <h5 class="card-title">$department</h5>
                <p class="card-text">$job</p>
            </div>
		</div>
	</div>
_END;
}
echo "</div";
echo "</div";

function getCastData($connection, $movieID) {

	$sql = "SELECT character_name, credit_name, profile_path FROM movie_cast INNER JOIN credits USING(credit_id) WHERE movie_ID=$movieID ORDER BY display_order ASC";

	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {

		return mysqli_fetch_all($result, MYSQLI_ASSOC);

	}
}

function getCrewData($connection, $movieID) {

	$sql = "SELECT department, job, credit_name, profile_path FROM movie_crew INNER JOIN credits USING(credit_id) WHERE movie_id=$movieID ORDER BY department ASC, job ASC, credit_name ASC";

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

require_once "footer.php";
?>