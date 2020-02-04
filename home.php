<?php

require_once "header.php";

// main page
echo <<<_END

<ul>

<li><a href = register.php>Register account</li>
<li><a href = create_database.php> create database </a></li>

</ul>
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
    
        <input class="btn" type="submit" value="Submit">
    </form> 
    </section>
    
<!-- <header class="homeHeader">

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
            
            <a class="btn" href="sign_in.php">Login or Sign up</a>
            
        </div>     
    </div>
</div>
</section>


<section class="search_movie js--wp-1">

<form action="/action_page.php" method="POST">
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

<input class="btn" type="submit" value="Submit">
</form> 
</section> -->

_END;

require_once "footer.php";

?>