<?php

require_once "header.php";


// main page
echo <<<_END

    <header class="homeHeader">

        <div class="headertitle"> 

            <h1>Movie Database presentation</h1>
            <a href="#" class="btno js-scroll-to-searchmovies">Search Movie</a>
        
        </div> 
       
    </header>

    
    <section class="test">
        <div class="insideDiv">
            <div class="row2">
                <div class="col2 span-1-of-22 norcol">
                    <h1>About the site</h1>
                    <p>Well come to the movie database presentation. This is a fully featured web app where you can browse and search content of the latest movies</p>
                </div>   
                <div class="col2 span-1-of-22 piccol">
                    <h1>Join the community</h1>
                    
                    <a class="btno" href="">Login or Sign up</a>
                    
                </div>     
            </div>
        </div>
    </section>
_END;


// creates connection to MYSQLi DB:
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// if the connection fails, we need to know, so allow this exit:
if (!$connection) {
	die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT title, movie_id, release_date, rating FROM `movie` WHERE votes > 100 ORDER BY rating DESC LIMIT 8";
$result = mysqli_query($connection, $query);
$n = mysqli_num_rows($result);



echo <<<_END
<section>
<h1>Highest rated movies</h1>
<br>
<div class="card-list">
_END;

/*

			<form action = 'admin.php' method = 'post'>

				<tr>
				<td>{$row['username']}</td>
				<td>{$row['password']}</td>
				<td>{$row['email']}</td>
				<td>{$row['firstname']}</td>
				<td>{$row['surname']}</td>
				<td>{$row['dob']}</td>
				<td>{$row['phone']}</td>
				<td> <a href="account_set.php?username=$usernameInfo">Update</td>
				<td> <a href="delete.php?username=$usernameInfo">Delete</td>
				</tr>

				</form>
*/
for($i=0; $i <$n; $i++){

    $row = mysqli_fetch_assoc($result);

    echo <<<_END
    
    <div class=card-container>

    <form action ="film.php" method="get">

    <a href="film.php?filmid={$row['movie_id']}" id="overlay">
    <p>Title: {$row['title']}</p>
    <br>
    <p>Date: {$row['release_date']}</p>
    <br>
    <p>Rating: {$row['rating']}</p>
    </a>
    </form>
    </div>

_END;


}


echo <<<_END
</div>
</section>
<br>
_END;


echo <<<_END

    <section class="search_movie js--wp-1">

        <form action="/action_page.php">
        <h1>Search Movie</h1>

        <div class="container">
            <input type="text" placeholder="Search...">
            <div class="search"></div>
        </div>

        <div class="wrapper">

            <div class="characterContent">
    
                <p>Movie Name:</p>
                <p>Genre:</p>
                <p>Original Language:</p>
                <p>Production companies:</p>
                <p>Production countries:</p>
                <p>Release date:</p>
                <p>Revenue:</p>
                <p>Rating:</p>
    
            </div>
    
            <div class="characterImage">
                <img class="characterImg" src="">
            </div>

        </div>
    
        <input class="btno" type="submit" value="Submit">
    </form> 
    </section>
    
_END;



require_once "footer.php";

?>