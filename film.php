<?php

// execute the header script:
require_once "header.php";


    //create connection
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    //The user that the admin wishs to delete
    $ID = $_GET['movie_id'];

    echo "$ID";
    
    if (!$connection){
        
        die("Connection failed: " . $mysqli_connect_error);
    }


?>