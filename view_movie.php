<?php

require_once "header.php";
require_once "helper.php";

if (isset($_GET['movieID'])) {
	$movieID = $_GET['movieID'];
} else {
	$movieID = 19995;
}

$movieMetadata = getMovieData($connection, $movieID);

echo "<title>{$movieMetadata['title']}</title>";

displayMetadata($connection, $movieID, $movieMetadata);

echo "<h2>Crew</h2>";
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
     	           <h4 class="card-title"><a href="view_credit.php?credit={$crewMember['credit_id']}">{$crewMember['credit_name']}</a></h4>
	                <h5 class="card-text">{$crewMember['department']}</h5>
	                <p class="card-text">{$crewMember['job']}</p>
	            </div>
            </div>
		</div>
	</div>
_END;
}
echo "</div></div";

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

	$sql = "SELECT department, job, credit_name, profile_path, credit_id FROM movie_crew INNER JOIN credits USING(credit_id) WHERE movie_id=$movieID ORDER BY department ASC, job ASC, credit_name ASC";

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

function getData($connection, $movieID, $tableName, $joinOn, $dataToGet, $orderBy) {

	$joinTable = substr($tableName, 6);

	$sql = "SELECT $dataToGet FROM $tableName INNER JOIN $joinTable USING ($joinOn) WHERE movie_id=$movieID ORDER BY $orderBy ASC";

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

function displayMetadata($connection, $movieID, $movieMetadata) {

	$releaseDate = date('d-m-Y', strtotime($movieMetadata['release_date']));
	$revenue = "$" . number_format($movieMetadata['revenue']);
	$budget = "$" . number_format($movieMetadata['budget']);

	echo "<div id='movieDataOuter'>";
	displayPicture($movieMetadata['poster_path'], "moviePoster");

	echo "<div id='movieDataInner'>";

	echo <<<_END
	<h1>{$movieMetadata['title']}</h1>
	<h3>{$movieMetadata['tagline']}</h3>
	<p>{$movieMetadata['overview']}</p>
	Rating: {$movieMetadata['rating']} ({$movieMetadata['votes']})
	<br>Release date: {$releaseDate}
	<br>Revenue: {$revenue}
	<br>Budget: {$budget}
	<br>Runtime {$movieMetadata['runtime']} minutes
_END;

	$genres = getData($connection, $movieID, "movie_genres", "genre_ID", "name", "name");

	echo "<br>Genre(s): ";

	$tempGenres = "";
	foreach ($genres as $curGenre) {
		$tempGenres = $tempGenres . $curGenre . ", ";
	}

	echo removeCommaFromListString($tempGenres);

//////////////////////////

	$languages = getData($connection, $movieID, "movie_languages", "iso_639", "name", "name");

	echo "<br>Language(s): ";

	$tempLanguage = "";
	foreach ($languages as $curLanguage) {
		$tempLanguage = $tempLanguage . $curLanguage . ", ";
	}

	echo removeCommaFromListString($tempLanguage);

//////////////////////////

	$prodCountries = getData($connection, $movieID, "movie_countries", "iso_3166", "country_name", "country_name");

	echo "<br>Production Country(s): ";

	$tempProdCountries = "";
	foreach ($prodCountries as $curCountry) {
		$tempProdCountries = $tempProdCountries . $curCountry . ", ";
	}

	echo removeCommaFromListString($tempProdCountries);

///////////////////////////

	$prodCompanies = getData($connection, $movieID, "movie_companies", "id", "company_name", "company_name");

	echo "<br>Production Companies: ";

	$tempProdCompanies = "";
	foreach ($prodCompanies as $curCompany) {
		$tempProdCompanies = $tempProdCompanies . $curCompany . ", ";
	}

	echo removeCommaFromListString($tempProdCompanies);

///////////////////////////

	$keywords = getData($connection, $movieID, "movie_keywords", "keyword_id", "name", "name");

	echo "<br>Keywords: ";

	$tempKeywords = "";
	foreach ($keywords as $curKeyword) {
		$tempKeywords = $tempKeywords . $curKeyword . ", ";
	}

	echo removeCommaFromListString($tempKeywords);

///////////////////////////

	echo "</div></div>";

	echo "<h2>Cast</h2>";
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
   	            	<h4 class="card-title"><a href="view_credit.php?credit={$castMember['credit_id']}">{$castMember['credit_name']}</a></h4>
                	<p class="card-text">{$castMember['character_name']}</p>
            	</div>
            </div>
		</div>
	</div>
_END;
	}
	echo "</div></div></div>";
}

require_once "footer.php";
?>