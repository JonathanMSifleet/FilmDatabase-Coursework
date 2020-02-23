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

$listOfFilms = getListOfFilms($connection, $creditID);

foreach ($listOfFilms as $curFilm) {
	$title = $curFilm['title'];
	$poster_path = $curFilm['poster_path'];
	$release_date = $curFilm['release_date'];
	$tagline = $curFilm['tagline'];
	$rating = $curFilm['rating'];
	$overview = $curFilm['overview'];
	$characterName = $curFilm['character_name'];

	$hasPicture = false;
	$imageData = "";
	if ($poster_path != "" || $poster_path != null) {
		$posterURL = "https://image.tmdb.org/t/p/original" . $poster_path;
		$imageData = base64_encode(file_get_contents($posterURL));
		$hasPicture = true;
	}

	echo <<<_END
	<div>
    	<div class="card">
        	<div class="card-body">
_END;

	if ($hasPicture) {
		echo '<img src="data:image/jpeg;base64,' . $imageData . '" height="auto" width="500px">';
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

displayPicture($profilePath);

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

function displayPicture($profilePath) {
	$pictureURL = "https://image.tmdb.org/t/p/original" . $profilePath;
	$imageData = base64_encode(file_get_contents($pictureURL));
	echo '<img class="card-img-top" src="data:image/jpeg;base64,' . $imageData . '" style="height: auto; width: 15vw;">';
}

require_once "footer.php";
?>