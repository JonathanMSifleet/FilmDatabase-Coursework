<?php
require_once "helper.php";

echo "<title>Search</title>";

if (!isset($_SESSION['gotLists'])) {
	$listOfLanguages = getListOfLanguages($connection);
	$listOfGenres = getListOfGenres($connection);
	$_SESSION['languages'] = $listOfLanguages;
	$_SESSION['genres'] = $listOfGenres;
	$_SESSION['gotLists'] = true;
}

if ($_SESSION['gotLists'] == true) {
	$listOfLanguages = $_SESSION['languages'];
	$listOfGenres = $_SESSION['genres'];
}

displayUI($connection, $listOfLanguages, $listOfGenres);

if (isset($_POST['minRating'])) {

	// get variables:

	$minRating = sanitise($_POST['minRating'], $connection);
	$minYear = sanitise($_POST['minYear'], $connection);
	$maxYear = sanitise($_POST['maxYear'], $connection);
	$minRuntime = sanitise($_POST['minRuntime'], $connection);
	$maxRuntime = sanitise($_POST['maxRuntime'], $connection);
	$minVotes = sanitise($_POST['minVotes'], $connection);
	$minBudget = sanitise($_POST['minBudget'], $connection);
	$minRevenue = sanitise($_POST['minRevenue'], $connection);

	$searchValue = sanitise($_POST['searchValue'], $connection);
	$orderDirection = sanitise($_POST['order'], $connection);
	$orderBy = sanitise($_POST['orderType'], $connection);

	if ($orderBy == "year") {
		$orderBy = "release_date";
	}

	if (isset($_POST['genreCheckboxes'])) {
		$genres = implode(',', $_POST['genreCheckboxes']);
	} else {
		$genres = "";
	}

	if (isset($_POST['languageCheckboxes'])) {
		$languages = implode("','", $_POST['languageCheckboxes']);
		$languages = "'" . $languages . "'";
	} else {
		$languages = "";
	}

	// search database:
	$query = <<<_END
	SELECT DISTINCT title FROM movie 
	INNER JOIN movie_genres USING (movie_ID)
	INNER JOIN genres USING (genre_ID)
	INNER JOIN movie_languages USING (movie_ID)
	INNER JOIN languages USING (iso_639)
	WHERE title LIKE '%{$searchValue}%' 
	AND rating > {$minRating} 
	AND (SUBSTR(release_date,1,4) BETWEEN {$minYear} AND {$maxYear})
	AND runtime BETWEEN {$minRuntime} AND {$maxRuntime}
	AND votes > {$minVotes}
	AND budget > {$minBudget}
	AND revenue > {$minRevenue}
_END;

	if ($genres !== "") {
		$query = <<<_END
		{$query}
		AND (genre_ID IN ({$genres}))
_END;
	}

	if ($languages !== "") {
		$query = <<<_END
		{$query}
		AND (iso_639 IN ({$languages}))
_END;
	}

	$query = $query . " ORDER BY {$orderBy} {$orderDirection}";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		echo "Number of results: " . mysqli_num_rows($result) . "<br>";

		while ($row = mysqli_fetch_array($result)) {
			print_r($row);
			echo "<br>";
		}
	}
}

function displayUI($connection, $listOfLanguages, $listOfGenres) {
	require_once "header.php";
	echo <<<_END
<form action="" id="filterForm" method="post">
	<div id="searchContentWrapper">
		<ul id='searchContent'>
			<li><input type="text" placeholder="Movie name" name="searchValue" minlength="0" maxlength="128" required></li>
			<li>Search for:</li>
			<li>
			<select name = "searchType">
				<option value = "name" selected>Name</option>
				<option value = "director">Director</option>
				<option value = "actorName">Actor name</option>
				<option value = "genre">Genre</option>
				<option value = "keyword">Keyword</option>
				<option value = "prodCompany">Production Company</option>
				<option value = "prodCountry">Country</option>
			</select>
			</li>
			<li> Order by:<br> </li>
			<li>
			<select name = "orderType">
				<option value = "popularity" selected>Popularity</option>
				<option value = "rating">Rating</option>
				<option value = "year">Year</option>
			</select>
			</li>
			<li>
				<ul id="radioList">
					<li>
						<input type="radio" class ='radio' name="order" value="ASC" id="asc" checked>Ascending
					</li>
					<li>
						<input type="radio" class='radio' name="order" value="DESC" id="desc">Descending
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class ='sidebar-sticky' id ="searchMovieSidebar"'>
		<div id = "filterTitle">
			<h3>Filters:</h3>
			<input type="submit" value="Submit" class="rounded" id="searchMovieButton">
		</div>

		<div id='accordion'>
			<div class="card">
                <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapseRating">Rating</a>
                </div>
                <div id="collapseRating" class="collapse toggle" data-parent="#accordion">
                    <div class="card-body">
						Minimum Rating: <br>
						<input type ="range" id="minRatingSlider" name="minRating" class="slider" min ="0" max ="10" step="0.1" value="0">
						<label id="minRatingLabel" for="minRatingSlider" class ="sliderLabel">0</label>
					</div>
				</div>
			</div>
_END;

	// year:
	$minYear = 1873;
	$maxYear = 2020;

	echo <<<_END
			<div class="card">
                <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapseYear">Year</a>
                </div>
                <div id="collapseYear" class="collapse toggle" data-parent="#accordion">
                    <div class="card-body">
						<label for="minYear">Minimum Year</label>
						<input type ="range" id="minYearSlider" name="minYear" class="slider" min ="$minYear" max ="$maxYear" step="1" value="0">
						<label id="minYearLabel" for="minYear" class ="sliderLabel">$minYear</label>
						<br><br><br>
						<label for="maxYear">Maximum Year</label>
						<input type ="range" id="maxYearSlider" name="maxYear" class="slider" min="$minYear" max ="$maxYear" step="1" value ="$maxYear">
                        <label id="maxYearLabel" for="maxYear" class ="sliderLabel">$maxYear</label>
                    </div>
				</div>
			</div>
_END;

	$minRuntime = getMinValue($connection, "runtime");
	$maxRuntime = getMaxValue($connection, "runtime");

	echo <<<_END
			<div class="card">
				<div class="card-header">
					<a class="collapsed card-link" data-toggle="collapse" href="#collapseRuntime">Runtime</a>
				</div>
				<div id="collapseRuntime" class="collapse toggle" data-parent="#accordion">
					<div class="card-body">
						<label for="minRuntimeSlider" >Minimum runtime</label>
						<input type ="range" id="minRuntimeSlider" name="minRuntime" class="slider"  id="minRuntimeSlider" class="slider" min ="$minRuntime" max ="$maxRuntime" value="$minRuntime">
						<label id="minRuntimeLabel" for="minRuntimeSlider" class ="sliderLabel">$minRuntime</label>
						<br><br><br>
						<label for="maxRuntime">Maximum runtime</label>
						<input type ="range" id="maxRuntimeSlider" name="maxRuntime" class="slider" min ="$minRuntime" max ="$maxRuntime" value="$maxRuntime">
						<label id="maxRuntimeLabel" for="maxRuntime" class ="sliderLabel">$maxRuntime</label>
					</div>
				</div>
			</div>
_END;

	$maxVotes = getMaxValue($connection, "votes");

	echo <<<_END
			<div class="card">
				<div class="card-header">
					<a class="collapsed card-link" data-toggle="collapse" href="#collapseVotes">Votes</a>
				</div>
				<div id="collapseVotes" class="collapse toggle" data-parent="#accordion">
					<div class="card-body">
						<label for="minVotesSlider">Minimum number of votes:</label>
						<input type ="range" id="minVotesSlider" name="minVotes" class="slider" min ="0" max ="$maxVotes" value="0">
						<label id="minVotesLabel" for="minVotesSlider" class ="sliderLabel">0</label>
					</div>
				</div>
			</div>
_END;

	$maxBudget = getMaxValue($connection, "budget");

	echo <<<_END
			<div class="card">
				<div class="card-header">
					<a class="collapsed card-link" data-toggle="collapse" href="#collapseBudget">Budget</a>
				</div>
				<div id="collapseBudget" class="collapse toggle" data-parent="#accordion">
					<div class="card-body">
						<label for="minBudgetSlider">Minimum</label>
						<input type ="range" id="minBudgetSlider" name="minBudget" class="slider" min ="0" max ="$maxBudget" value ="0">
						<br>
						<label id="minBudgetLabel" for="minBudgetSlider" class ="sliderLabel">0</label>
						<br><br><br>
						<label for="maxBudgetSlider">Maximum</label>
						<input type ="range" id="maxBudgetSlider" name ="maxBudget" class="slider" min="0" max ="$maxBudget" value="$maxBudget">
						<label id="maxBudgetLabel" for="maxBudgetSlider" class ="sliderLabel">$maxBudget</label>
					</div>
				</div>
			</div>
_END;

	$maxRevenue = getMaxValue($connection, "revenue");

	echo <<<_END
			<div class="card">
                <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapseRevenue">Revenue</a>
                </div>
                <div id="collapseRevenue" class="collapse toggle" data-parent="#accordion">
                    <div class="card-body">
						<label for="minRevenueSlider">Minimum</label>
						<input type ="range" id="minRevenueSlider" name="minRevenue" class="slider" min ="0" max ="$maxRevenue" value="0">
						<br>
						<label id="minRevenueLabel" for="minRevenueSlider" class ="sliderLabel">0</label>
						<br>
                    </div>
				</div>
			</div>
			
			<div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapseGenres">Genres</a>
                </div>
                <div id="collapseGenres" class="collapse toggle" data-parent="#accordion">
                    <div class="card-body">
						<ul style='list-style-type: none;'>
_END;
	echo "<ul style='list-style-type: none;  min-width: 50%; word->";
	foreach ($listOfGenres as $curGenre) {
		echo "<li><input type='checkbox' class='boxes' name='genreCheckboxes[]' value =" . $curGenre['genre_ID'] . ">" . $curGenre['name'] . "</input></li>";
	}
	echo "</ul>";

	echo <<<_END
					</div>
                </div>
			</div>
			
            <div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapseLanguages">Languages</a>
                </div>
                <div id="collapseLanguages" class="collapse toggle" data-parent="#accordion">
                    <div class="card-body">
_END;

	echo "<ul style='list-style-type: none;  min-width: 50%; word->";
	foreach ($listOfLanguages as $curLanguage) {
		echo "<li><input type='checkbox' name='languageCheckboxes[]' class='boxes' value =" . $curLanguage['iso_639'] . ">" . $curLanguage['name'] . "</input></li>";
	}
	echo "</ul>";

	echo <<<_END
						</div>
                    </div>
				</div>
            </div>
        </div>
</form>
_END;

}

function getMinValue($connection, $maxValToFind) {

	$query = "SELECT MIN($maxValToFind) FROM movie";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$row = mysqli_fetch_array($result);
		return $row[0];
	}

}

function getMaxValue($connection, $maxValToFind) {

	$query = "SELECT MAX($maxValToFind) FROM movie";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$row = mysqli_fetch_array($result);
		return $row[0];
	}

}

function getListOfLanguages($connection) {
	$query = "SELECT name, iso_639 FROM languages ORDER BY name ASC";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$listOfLanguages = array();

		while ($row = mysqli_fetch_assoc($result)) {
			$listOfLanguages[] = $row;
		}

		return $listOfLanguages;

	}
}

function getListOfGenres($connection) {
	$query = "SELECT name, genre_ID FROM genres ORDER BY name ASC";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {

		$listOfGenres = array();

		while ($row = mysqli_fetch_assoc($result)) {
			$listOfGenres[] = $row;
		}

		return $listOfGenres;

	}
}

?>