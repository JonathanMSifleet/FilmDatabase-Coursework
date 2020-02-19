<?php

require_once "header.php";

// main page
echo <<<_END

    <header class="homeHeader">

        <div class="headertitle"> 

            <h1>Movie Database presentation</h1>
            <a href="#" class="btn js-scroll-to-searchmovies">Search Movie</a>
        
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
                    
                    <a class="btn" href="">Login or Sign up</a>
                    
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
<div class="Card-list">
_END;

for($i=0; $i <$n; $i++){

    $row = mysqli_fetch_assoc($result);

    echo <<<_END
    
    <div class="Card-container">

    <a href="view_movie.php?movieID={$row['movie_id']}" id="overlay">
    <p>Title: {$row['title']}</p>
    <br>
    <p>Date: {$row['release_date']}</p>
    <br>
    <p>Rating: {$row['rating']}</p>
    </a>

    </div>

_END;


}

echo <<<_END
</div>
</section>

_END;

echo <<<_END
    <section class="search_movie js--wp-1">

        <form action="view_movie.php?movieID=2">
        <h1>Search Movie</h1>

        <div class="container">
            <input type="text" placeholder="Search...">
            <div class="search"></div>
        </div>
    
        <input class="btn" type="submit" value="Search">
    </form> 
    </section>  
_END;

// creates connection to MYSQLi DB:
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// if the connection fails, we need to know, so allow this exit:
if (!$connection) {
	die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM `movie` order by RAND() LIMIT 8";
$result = mysqli_query($connection, $query);
$n = mysqli_num_rows($result);



echo <<<_END
<section>
<h1>Something to watch</h1>
<br>
<div class="Card-list">
_END;

for($i=0; $i <$n; $i++){

    $row = mysqli_fetch_assoc($result);

    echo <<<_END
    
    <div class="Card-container">

    <a href="view_movie.php?movieID={$row['movie_id']}" id="overlay">
    <p>Title: {$row['title']}</p>
    <br>
    <p>Date: {$row['release_date']}</p>
    <br>
    <p>Rating: {$row['rating']}</p>
    </a>

    </div>

_END;

}
echo <<<_END
<br>
</div>
</section>
_END;


echo <<<_END
<section>
<form action="view_movie.php" method="POST" autocomplete="off">
    <label>Search a movie:</label>
    <input type="text" name="title" id="search_movie" />
 
</form>

<div class="col-md-5" style="position: relative; margin-top: -10px; margin-left:35px ">
    <div class="list-group" id="show-list">
   
    </div>
</div>
</section>
<br>
<br>
<br>
<br>
<br>
_END;

echo <<<_END
<script type="text/javascript">

$(document).ready(function(){

    $("#search_movie").keyup(function(){
        var searchText = $(this).val();

        if(searchText != ''){
            $.ajax({
                url:'autoc_movie.php',
                method: 'GET',
                data: {query: searchText},
                success:function(response){
                    $("#show-list").html(response);
                }

            });
        }
        else{
            $("#show-list").html('');
        }
    });    
  
});
</script>
_END;

require_once "footer.php";

?>