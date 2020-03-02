<?php

require_once "header.php";

$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// if the connection fails, we need to know, so allow this exit:
if (!$connection) {
	die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['movieID'])) {
	$movieID = $_GET['movieID'];
} else {
	$movieID = 19995;
}

$movieMetadata = getMovieData($connection, $movieID);

echo "<title>" . $movieMetadata['title'] . "</title>";

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

echo "<br><br><h2>Cast</h2>";
$castData = getCastData($connection, $movieID);

echo "<div class='container-fluid'>";
echo "<div class='row justify-content-center'>";

foreach ($castData as $castMember) {
	$profilePath = $castMember['profile_path'];
	echo <<<_END
	<div class="cardContainerOuter">
    	<div class="cardContainerInner rounded">
        	<div class="card">
_END;

	if ($profilePath != "" || $profilePath != null) {
		displayPicture($profilePath, "movieCreditCard");
	}

	echo <<<_END
	            <div class="card-body">
   	            	<a href="view_actor.php?credit={$castMember['credit_id']}"><h4 class="card-title">{$castMember['credit_name']}</h4></a>
                	<p class="card-text">{$castMember['character_name']}</p>
            	</div>
            </div>
		</div>
	</div>
_END;
}
echo "</div></div</div>";

///////////////////

echo "<br><br><h2>Crew</h2>";
$crewData = getCrewData($connection, $movieID);

echo "<div class='container-fluid'>";
echo "<div class='row justify-content-center'>";

foreach ($crewData as $crewMember) {
	$profilePath = $crewMember['profile_path'];

	echo <<<_END
	<div class="cardContainerOuter">
    	<div class="cardContainerInner rounded">
        	<div class="card">
_END;

	if ($profilePath != "" || $profilePath != null) {
		displayPicture($profilePath, "movieCreditCard");
	}

	echo <<<_END
	            <div class="card-body">
	                <h4 class="card-title">{$crewMember['credit_name']}</h4>
	                <h5 class="card-text">{$crewMember['department']}</h5>
	                <p class="card-text">{$crewMember['job']}</p>
	            </div>
            </div>
		</div>
	</div>
_END;
}
echo "</div";
echo "</div";

function getCastData($connection, $movieID) {

	$sql = "SELECT credit_id, character_name, credit_name, profile_path FROM movie_cast INNER JOIN credits USING(credit_id) WHERE movie_ID=$movieID ORDER BY display_order ASC";

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

require_once "footer.php";
?>