<?php

require_once "header.php";

$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// if the connection fails, we need to know, so allow this exit:
if (!$connection) {
	die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['credit'])) {
	$creditID = $_GET['credit'];
} else {
	$creditID = 3896;
}

$creditData = getCreditData($connection, $creditID);

foreach ($creditData as $curCredit) {
	echo "<h1>{$curCredit['credit_name']}</h1>";
	displayPicture($curCredit['profile_path'], "viewActorImage");
	break;
}

$listOfFilms = getListOfFilms($connection, $creditID);

echo "<div class='container-fluid'>";
echo "<div class='row justify-content-center'>";

foreach ($listOfFilms as $curFilm) {
	$poster_path = $curFilm['poster_path'];

	$releaseDate = substr($curFilm['release_date'], 0, 4);

	echo <<<_END
	<div class="cardContainerOuter">
    	<div class="cardContainerInner rounded">
        	<div class="card">
_END;

	if ($poster_path != "" || $poster_path != null) {
		displayPicture($poster_path, "movieCreditCard");
	}

	echo <<<_END
	            <div class="card-body">
	                <h4 class="card-title"><a href="view_movie.php?movieID={$curFilm['movie_id']}">{$curFilm['title']} ($releaseDate)</a></h4>
	                <h5 class="card-title">{$curFilm['character_name']}</h5>
            	</div>
            </div>
		</div>
	</div>
_END;
}
echo "</div></div</div>";

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

	$sql = "SELECT DISTINCT title, poster_path, release_date, character_name, movie_id FROM movie INNER JOIN movie_cast USING (movie_id) WHERE credit_id = '$creditID' ORDER BY popularity DESC";
	$result = mysqli_query($connection, $sql);

	if (!$result) {
		echo "<br>" . mysqli_error($connection);
	} else {
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

}

require_once "footer.php";
?>