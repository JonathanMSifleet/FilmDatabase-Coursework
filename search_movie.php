<?php

require_once "header.php";

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

// initialise variables:

//////////

displayUI($connection, $listOfLanguages, $listOfGenres);

if (isset($_POST['minRating'])) {

	// get variables:

	$minRating = $_POST['minRating'];
	$minPopularity = $_POST['minPopularity'];
	$minYear = $_POST['minYear'];
	$maxYear = $_POST['maxYear'];
	$minRuntime = $_POST['minRuntime'];
	$maxRuntime = $_POST['maxRuntime'];
	$minVotes = $_POST['minVotes'];
	$minBudget = $_POST['minBudget'];
	$minRevenue = $_POST['minRevenue'];
	$maxRevenue = $_POST['maxRevenue'];

	echo <<<_END
		$minRating
		$minPopularity
		$minYear 
		$maxYear
		$minRuntime
		$maxRuntime
		$minVotes
		$minBudget
		$minRevenue
		$maxRevenue
_END;


	// search database:

}
//////////////////////

function displayUI($connection, $listOfLanguages, $listOfGenres
) {
	echo <<<_END
<!-- Sidebar: -->

<form action="" id="filterForm" method="post" style='width: 15%; margin: 0; padding: 0; float: left;'>
	<div class ='sidebar-sticky' style='width: 100%; background-color: #ff726f;'>
		<div id='sidebarContent'>
			<h2>Filters:</h2>
			<input type="submit" value="Submit">
			<br><br>
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

	$maxPopularity = getMaxValue($connection, "popularity");

	echo <<<_END
				<br>
				<div class="card">
	                <div class="card-header">
	                    <a class="collapsed card-link" data-toggle="collapse" href="#collapsePopularity">Popularity</a>
	                </div>
	                <div id="collapsePopularity" class="collapse toggle" data-parent="#accordion">
	                    <div class="card-body">
							Minimum popularity: <br>
							<input type ="range" id="minPopSlider" name="minPopularity" class="slider" min ="0" max ="$maxPopularity" value="0">
							<label id="minPopLabel" for="minPopSlider" class ="sliderLabel">0</label>
	                    </div>
	                </div>
				</div>
_END;

	// year:
	$minYear = 1873;
	$maxYear = 2020;

	echo <<<_END
				<br>
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
				<br>
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
				<br>
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
					<br>
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
					<br>
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
								<br><br><br>
								<label for="maxRevenueSlider">Maximum</label>
								<input type ="range" id="maxRevenueSlider" name="maxRevenue" class="slider" min="0" max ="$maxRevenue" value ="$maxRevenue">
								<label id="maxRevenueLabel" for="maxRevenueSlider" class ="sliderLabel">$maxRevenue</label>
	                        </div>
						</div>
					</div>
					<br>
					
					<div class="card">
	                    <div class="card-header">
	                        <a class="card-link" data-toggle="collapse" href="#collapseGenres">Genres</a>
	                    </div>
	                    <div id="collapseGenres" class="collapse toggle" data-parent="#accordion">
	                        <div class="card-body">
								<ul style='list-style-type: none;'>
_END;

echo "<ul  style='list-style-type: none; text-align= left;'>";
	echo "<li><input type='checkbox' class='boxes' name='$curGenre' id ='$curGenre' value ='$curGenre'>$curGenre</input></li>";
	}
	echo "</ul>";

	echo <<<_END
							</div>
	                    </div>
					</div>
					<br>
					
	                <div class="card">
	                    <div class="card-header">
	                        <a class="card-link" data-toggle="collapse" href="#collapseLanguages">Languages</a>
	                    </div>
	                    <div id="collapseLanguages" class="collapse toggle" data-parent="#accordion">
	                        <div class="card-body">
_END;

	echo "<ul style='list-style-type: none;  min-width: 50%; word->";
	foreach ($listOfLanguages as $curLanguage) {
		echo "<li><input type='checkbox' name='$curLanguage' class='boxes' id ='$curLanguage' value ='$curLanguage'>$curLanguage</input></li>";
	}
	echo "</ul>";

	echo <<<_END
							</div>
	                    </div>
					</div>
	            </div>
	        </div>
		<br>
	</div>
</form>

<div id='searchBox'>
	<div id='searchContent'>
	<ul>
		<li><input type="text" placeholder="Search" minlength="0" maxlength="128" required></li>
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
			<option value = "genre">Genre</option>
			<option value = "year">Year</option>
		</select>
		</li>
		<li><input type="radio" class ='radio' name="order" value="asc" checked>Ascending</li>
		<li><input type="radio" class='radio' name="order" value="desc">Descending</li>
	</ul>
	</div>
</div>
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
	$query = "SELECT name FROM languages ORDER BY name ASC";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$listOfLanguages = array();

		while ($row = mysqli_fetch_array($result)) {
			$listOfLanguages[] = $row[0];
		}

		return $listOfLanguages;

	}
}

function getListOfGenres($connection) {
	$query = "SELECT name FROM genres ORDER BY name ASC";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$listOfGenres = array();

		while ($row = mysqli_fetch_array($result)) {
			$listOfGenres[] = $row[0];
		}

		return $listOfGenres;

	}
}

?>