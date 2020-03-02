<?php

require_once "header.php";

if (isset($_POST['credit'])) {

	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}

} else if (isset($_GET['credit'])) {
	$creditID = $_GET['credit'];
} else {
	$creditID = 3896;
}

$creditData = getCreditData($connection, $creditID);

$creditName = "";
$profilePath = "";

foreach ($creditData as $curCredit) {
	$creditName = $curCredit['credit_name'];
	$profilePath = $curCredit['profile_path'];
	break;
}

echo "<h1>$creditName</h1>";
displayPicture($profilePath, "viewActorImage");


$listOfFilms = getListOfFilms($connection, $creditID);

foreach ($listOfFilms as $curFilm) {
	$title = $curFilm['title'];
	$poster_path = $curFilm['poster_path'];
	$release_date = $curFilm['release_date'];
	$tagline = $curFilm['tagline'];
	$rating = $curFilm['rating'];
	$overview = $curFilm['overview'];
	$characterName = $curFilm['character_name'];

	echo <<<_END
	<div>
    	<div class="card">
        	<div class="card-body">
_END;

	if ($poster_path != "" || $poster_path != null) {
		displayPicture($poster_path, "moviePoster");
	}

	echo <<<_END
                <h4 class="card-title">$title</h4>
                <h5 class="card-title">$characterName</h5>
                <p class="card-text">$overview</p>
            </div>
		</div>
	</div>
_END;

}

function getCreditData($connection, $creditID) {

	$sql = "SELECT credit_name, profile_path FROM credits WHERE credit_id = '$creditID'";
	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
}

function getListOfFilms($connection, $creditID) {

	$sql = "SELECT DISTINCT title, poster_path, release_date, tagline, rating, overview, character_name FROM movie INNER JOIN movie_cast USING (movie_id) WHERE credit_id = '$creditID' ORDER BY popularity DESC";
	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

}

require_once "footer.php";
?>