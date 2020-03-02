<?php

    require_once("helper.php");
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
    }
    
    if(isset($_GET['query'])){

        $inpText = sanitise($_GET['query'], $connection);

        $query = "SELECT title, movie_id, YEAR(release_date) AS 'year' FROM `movie` WHERE title LIKE '%$inpText%' LIMIT 10";

        $result = $connection->query($query);

        if($result->num_rows > 0){
            while($row=$result->fetch_assoc()){
                echo <<<_END
                <a href="view_movie.php?movieID={$row['movie_id']}" style="margin: 0" class="list-group-item list-group-item-action border-1"> {$row['title']} - ({$row['year']})</a>
_END;
            }
        }
        else{
            echo <<<_END
            <p class='list-group-item border-1> No such movie</p>";
_END;
        }

    }

?>