<?php
require_once "header.php";
require_once "helper.php";
?>

    <title>MDPT</title>
    <header class="homeHeader">

        <div class="headertitle">

            <h1>Movie Database presentation</h1>
            <a href="#" class="btn1 js-scroll-to-searchmovies">Search Movie</a>

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
                    <h1>Browse your favourite Movies</h1>

                </div>
            </div>
        </div>
    </section>

    <section class="js--wp-1">
        <form action="view_movie.php" method="POST" autocomplete="off" id="homeSearchMovie">
            <label>Search a movie:</label>
            <input type="text" name="title" id="search_movie"/>

        </form>

        <div class="col-md-5" style="position: relative; margin-top: -60px; margin-left:390px ">
            <div class="list-group" id="show-list" style="text-align: center">

            </div>
        </div>

    </section>

<?php

$query = "SELECT title, movie_id, release_date, rating FROM `movie` WHERE votes > 100 ORDER BY rating DESC LIMIT 8";
$result = mysqli_query($connection, $query);
$n = mysqli_num_rows($result);

echo <<<_END
<section>
<h1>Highest rated movies</h1>
<br>
<div class="Card-list">
_END;

for ($i = 0; $i < $n; $i++) {

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
?>

    </div>
    </section>

<?php

$query = "SELECT * FROM `movie` order by RAND() LIMIT 8";
$result = mysqli_query($connection, $query);
$n = mysqli_num_rows($result);

echo <<<_END
<section>
<h1>Something to watch</h1>
<br>
<div class="Card-list">
_END;

for ($i = 0; $i < $n; $i++) {

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

?>
    <br>
    </div>
    </section>


    <script type="text/javascript">

        $(document).ready(function () {

            $("#search_movie").keyup(function () {
                var searchText = $(this).val();

                if (searchText != '') {
                    $.ajax({
                        url: 'autoc_movie.php',
                        method: 'GET',
                        data: {query: searchText},
                        success: function (response) {
                            $("#show-list").html(response);
                        }

                    });
                } else {
                    $("#show-list").html('');
                }
            });

        });
    </script>

<?php

require_once "footer.php";

?>