<?php

require_once "header.php";

echo "<div id='search' style='float: right; clear: both;'>";

echo <<<_END
Search for: <br>
<select name = "searchType">

<option value = "name" selected>Name</option>
<option value = "actorName">Actor name</option>
<option value = "genre">Genre</option>
<option value = "keyword">Keyword</option>
<option value = "prodCompany">Production Company</option>
<option value = "prodCountry">Country</option>

</select>
_END;

// sort by:
echo <<<_END
<br><br>
Order by:<br>
<select name = "orderType">

<option value = "popularity" selected>Popularity</option>
<option value = "rating">Rating</option>
<option value = "genre">Genre</option>
<option value = "year">Year</option>

</select>
_END;

echo <<<_END
<br>
<input type="radio" name="order" value="asc" checked>Ascending<br>
<input type="radio" name="order" value="desc">Descending<br>

_END;


echo "</div>";

echo "<div id='filters' style='float: left;'>";

// rating:
echo <<<_END
<br><br>
Minimum Rating: <br>
<input type ="range" id="minRating" name ="minRating" min ="0" max ="10" step="0.1" value="0">
_END;

// popularity:
$maxPopularity = getMaxValue($connection, "popularity");
echo <<<_END
<br><br>
Minimum popularity:<br>
<input type ="range" id="minPop" name ="minPop" min ="0" max ="$maxPopularity" value="0">
_END;

// year:
$minYear = 1873;
$maxYear = 2020;
echo <<<_END
<br><br>
Minimum year: <br>
<input type ="range" id="minYear" name ="minYear" min ="$minYear" max ="$maxYear" value="0">
<label for="minYear">Minimum Year</label>

<br>
<input type ="range" id="maxYear" name="maxYear" min="$minYear" max ="$maxYear" value ="$maxYear">
<label for="maxBudget">Maximum Year</label>
_END;

// runtime:
$maxRuntime = getMaxValue($connection, "runtime");
echo <<<_END
<br><br>
Runtime: <br>
<input type ="range" id="minRuntime" name ="minRuntime" min ="0" max ="$maxYear" value="0">
<label for="minYear">Minimum runtime</label>

<br>
<input type ="range" id="maxYear" name="maxYear" min="$minYear" max ="$maxYear" value ="$maxYear">
<label for="maxBudget">Maximum runtime</label>
_END;

// number of votes:
$maxVotes = getMaxValue($connection, "votes");
echo <<<_END
<br><br>
Minimum number of votes:<br>
<input type ="range" id="minVotes" name ="minVotes" min ="0" max ="$maxVotes" value="0">
_END;

// budget:
$maxBudget = getMaxValue($connection, "budget");
echo <<<_END
<br><br>
Budget: <br>
<input type ="range" id="minBudget" name ="minBudget" min ="0" max ="$maxBudget" value ="0">
<label for="minBudget">Minimum</label>

<br>
<input type ="range" id="maxBudget" name="maxBudget" min="0" max ="$maxBudget" value="$maxBudget">
<label for="maxBudget">Maximum</label>
_END;

// revenue:
$maxRevenue = getMaxValue($connection, "revenue");
echo <<<_END
<br><br>
Revenue: <br>
<input type ="range" id="minRevenue" name ="minRevenue" min ="0" max ="$maxRevenue" value="0">
<label for="minBudget">Minimum</label>

<br>
<input type ="range" id="maxRevenue" name="maxRevenue" min="0" max ="$maxRevenue" value ="$maxRevenue">
<label for="maxBudget">Maximum</label>
_END;


// languages:
$listOfLanguages = getListOfLanguages($connection);
echo "<br><br>";
echo "Languages:<br>";
foreach ($listOfLanguages as $curLanguage) {
	echo "<input type='checkbox' name='[add]' value ='$curLanguage'>$curLanguage</input><br>";
}

/* production companies:
$listOfProductionCompanies = getListOfProdCompanies($connection);
echo "<br><br>";
echo "Production companies:<br>";
foreach($listOfProductionCompanies as $curCompany) {
	echo "<input type='checkbox' name='[add]' value ='$curCompany'>$curCompany</input><br>";
} */


// production country:
$listOfProdCountries = getListOfProdCountries($connection);
echo "Production countries:<br>";
foreach ($listOfProdCountries as $curCountry) {
	echo "<input type='checkbox' name='[add]' value ='$curCountry'>$curCountry</input><br>";
}


echo "</div>";

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
	$query = "SELECT name FROM languages GROUP BY iso_639 ORDER BY name ASC";
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

function getListOfProdCompanies($connection) {
	$query = "SELECT DISTINCT(companyName) FROM companies GROUP BY id ORDER BY companyName ASC";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$listOfProdCompanies = array();

		while ($row = mysqli_fetch_array($result)) {
			$listOfProdCompanies[] = $row[0];
		}

		return $listOfProdCompanies;

	}
}

function getListOfProdCountries($connection) {
	$query = "SELECT DISTINCT(name) FROM countries GROUP BY iso_3166 ORDER BY name ASC";
	$result = mysqli_query($connection, $query);

	if (!$result) {
		echo mysqli_error($connection);
	} else {
		$listOfProdCompanies = array();

		while ($row = mysqli_fetch_array($result)) {
			$listOfProdCompanies[] = $row[0];
		}

		return $listOfProdCompanies;

	}
}

?>