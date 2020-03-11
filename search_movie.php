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

	$searchParameters = array();
	$searchParameters['minRating'] = sanitise($_POST['minRating'], $connection);
	$searchParameters['minYear'] = sanitise($_POST['minYear'], $connection);
	$searchParameters['maxYear'] = sanitise($_POST['maxYear'], $connection);
	$searchParameters['minRuntime'] = sanitise($_POST['minRuntime'], $connection);
	$searchParameters['maxRuntime'] = sanitise($_POST['maxRuntime'], $connection);
	$searchParameters['minVotes'] = sanitise($_POST['minVotes'], $connection);
	$searchParameters['minBudget'] = sanitise($_POST['minBudget'], $connection);
	$searchParameters['minRevenue'] = sanitise($_POST['minRevenue'], $connection);
	$searchParameters['searchValue'] = sanitise($_POST['searchValue'], $connection);
	$searchParameters['orderDirection'] = sanitise($_POST['order'], $connection);
	$searchParameters['orderBy'] = sanitise($_POST['orderType'], $connection);
	$searchParameters['searchType'] = sanitise($_POST['searchType'], $connection);
	$searchParameters['showNullResults'] = false;

	$query = buildQuery($searchParameters);
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$resultsToDisplay = array();

		while ($row = mysqli_fetch_array($result)) {
			$resultsToDisplay[] = $row;
		}

		$numResults = count($resultsToDisplay);

		echo <<<_END
	<div id = 'searchResults'>
		<p>Searching for "{$searchParameters['searchValue']}"</p>
		<p>Number of results: {$numResults}</p>
		<table id="resultsTable">
_END;
		
		if ($searchParameters['searchType'] == "name") {
			displayMovieResults($resultsToDisplay);
		} else {
			displayPersonResults($resultsToDisplay);
		}
		echo "</table>";
		echo "</div>";
	}
}

function displayUI($connection, $listOfLanguages, $listOfGenres) {
	require_once "header.php";
	echo <<<_END
<form action="" id="filterForm" method="post">
<div id="searchContentWrapper">
	<ul id='searchContent'>
		<li><input type="text" placeholder="Search..." name="searchValue" minlength="0" maxlength="128"></li>
		<li>Search for:</li>
		<li>
		<select id="searchType" name="searchType">
			<option value = "name" selected>Movie name</option>
			<option value = "director">Director</option>
			<option value = "actorName">Actor name</option>
		</select>
		</li>
		<li> Order by:<br> </li>
		<li>
		<select id="orderType" name = "orderType">
			<option value = "rating">Rating</option>
			<option value = "popularity">Popularity</option>
			<option value = "year" selected>Year</option>
			<option value = "runtime">Runtime</option>
			<option value = "budget">Budget</option>
			<option value = "revenue">Revenue</option>
		</select>
		</li>
		<li>
			<ul id="radioList">
				<li>
					<input type="radio" class ='radio' name="order" value="ASC" id="asc">Ascending
				</li>
				<li>
					<input type="radio" class='radio' name="order" value="DESC" id="desc" checked>Descending
				</li>
			</ul>
		</li>
		<li>
			<input type="checkbox" name="showNullResults" checked> Show films with missing data
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
					<input type ="range" id="minBudgetSlider" name="minBudget" class="slider" min ="0" max ="$maxBudget" step="1000" value ="0">
					<br>
					<label id="minBudgetLabel" for="minBudgetSlider" class ="sliderLabel">0</label>
					<br><br><br>
					<label for="maxBudgetSlider">Maximum</label>
					<input type ="range" id="maxBudgetSlider" name ="maxBudget" class="slider" min="0" max ="$maxBudget" step="1000" value="$maxBudget">
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
					<input type ="range" id="minRevenueSlider" name="minRevenue" class="slider" min ="0" max ="$maxRevenue" step="1000" value="0">
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

function displayMovieResults($results) {

	echo "<th>Title</th><th>Release date</th><th>Rating</th><th>Runtime (minutes) </th><th>Revenue ($)</th><th>Budget ($)</th>";

	foreach ($results as $curResult) {

		$runtime = $curResult['runtime'];
		if ($runtime == null or $runtime == "") {
			$runtime = "Unknown";
		}

		$releaseDate = $curResult['release_date'];
		if ($releaseDate == null or $releaseDate == "") {
			$releaseDate = "Unknown";
		} else {
			$releaseDate = date('d-m-Y', strtotime($curResult['release_date']));
		}

		$revenue = $curResult['revenue'];
		if ($revenue == null or $revenue == "") {
			$revenue = "Unknown";
		} else {
			$revenue = number_format($revenue);
		}

		$budget = $curResult['budget'];
		if ($budget == null or $budget == "") {
			$budget = "Unknown";
		} else {
			$budget = number_format($budget);
		}

		echo "<tr>";
		echo "<td><a href = 'view_movie.php?movieID={$curResult['movie_id']}'>" . $curResult['title'] . "</a></td><td>{$releaseDate}</td><td>{$curResult['rating']}</td><td>{$runtime}</td><td>{$revenue}</td><td>{$budget}</td>";
		echo "</tr>";
	}
}

function displayPersonResults($results) {

	echo "<th>Name</th>";

	foreach ($results as $curResult) {
		echo "<tr>";
		echo "<td><a href = 'view_credit.php?credit={$curResult['credit_id']}'>" . $curResult['credit_name'] . "</a></td>";
		echo "</tr>";
	}
}

function buildQuery($searchParameters) {
	if (!empty($_POST['showNullResults'])) {
		$searchParameters['showNullResults'] = true;
	}

	if ($searchParameters['orderBy'] == "year") {
		$searchParameters['orderBy'] = "release_date";
	}

	$searchParameters['genres'] = "";
	if (isset($_POST['genreCheckboxes'])) {
		$searchParameters['genres'] = implode(',', $_POST['genreCheckboxes']);
	}

	$searchParameters['languages'] = "";
	if (isset($_POST['languageCheckboxes'])) {
		$searchParameters['languages'] = implode("','", $_POST['languageCheckboxes']);
		$searchParameters['languages'] = "'" . $searchParameters['languages'] . "'";
	}

	$query = "";
	switch ($searchParameters['searchType']) {
		case "name" :
			$query = $query = "SELECT DISTINCT title, release_date, movie_id, revenue, budget, runtime, rating FROM movie 
	LEFT OUTER JOIN movie_genres USING (movie_ID)
	LEFT OUTER JOIN genres USING (genre_ID)
	LEFT OUTER JOIN movie_languages USING (movie_ID)
	LEFT OUTER JOIN languages USING (iso_639)
	WHERE title LIKE '%{$searchParameters['searchValue']}%'";
			break;
		case "director" :
			$query = "SELECT DISTINCT credit_name, credit_id FROM credits INNER JOIN movie_crew USING (credit_id) WHERE credit_name LIKE '%{$searchParameters['searchValue']}%' AND job='director'";
			break;
		case "actorName" :
			$query = "SELECT DISTINCT credit_name, credit_id FROM credits INNER JOIN movie_cast USING (credit_id) WHERE credit_name LIKE '%{$searchParameters['searchValue']}%'";
			break;
	}

	$query = $query . addFilters($searchParameters);

	if ($searchParameters['genres'] !== "") {
		$query = $query . " AND (genre_ID IN ({$searchParameters['genres']}))";
	}

	if ($searchParameters['languages'] !== "") {
		$query = $query . " AND (iso_639 IN ({$searchParameters['languages']}))";
	}

	return $query . " ORDER BY `{$searchParameters['orderBy']}` {$searchParameters['orderDirection']}";

}

function addFilters($searchParameters) {

	$filters = "";

	if ($searchParameters['searchType'] == "name") {
		if (!$searchParameters['showNullResults']) {
			$filters = $filters . <<<_END
	AND rating > {$searchParameters['minRating']} 
	AND (SUBSTR(release_date,1,4) BETWEEN {$searchParameters['minYear']} AND {$searchParameters['maxYear']})
	AND runtime BETWEEN {$searchParameters['minRuntime']} AND {$searchParameters['maxRuntime']}
	AND votes > {$searchParameters['minVotes']}
	AND budget > {$searchParameters['minBudget']}
	AND revenue > {$searchParameters['minRevenue']}";
_END;
		} else {
			$filters = $filters . <<<_END
	AND (SUBSTR(release_date,1,4) BETWEEN {$searchParameters['minYear']} AND {$searchParameters['maxYear']} OR release_date IS NULL)
	AND (rating > {$searchParameters['minRating']} OR rating IS NULL)
	AND (runtime BETWEEN {$searchParameters['minRuntime']} AND {$searchParameters['maxRuntime']} OR runtime IS NULL)
	AND (votes > {$searchParameters['minVotes']} OR votes IS NULL)
	AND (budget > {$searchParameters['minBudget']} OR budget IS NULL)
	AND (revenue > {$searchParameters['minRevenue']} OR revenue IS NULL)
_END;
		}
	}
	return $filters;

}

?>


