<?php

// execute the header script:
require_once "header.php";

// checks the session variable named 'loggedInSkeleton'
if (isset($_SESSION['loggedInSkeleton'])) {
    // user is already logged in, just display a message:
    echo "You are already logged in, please log out if you wish to create a new account<br>";
} else {

    // creates connection to MYSQLi DB:
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // if the connection fails, we need to know, so allow this exit:
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $arrayOfAccountCreationErrors = array();
    initEmptyArray($arrayOfAccountCreationErrors, 4);

    // default values we show in the form:
    $username = "";
    $email = "";
    $password = "";
    $firstname = "";
    $surname = "";

    if (isset($_POST['username'])) {
        // user just tried to sign up:
        sanitiseUserData($connection, $username, $email, $password, $firstname, $surname);
        createAccount($connection, $username, $email, $password, $firstname, $surname, $arrayOfAccountCreationErrors);
    } else {
        // just a normal visit to the page, show the signup form:
        displayCreateAccountForm($username, $email, $password, $firstname, $surname, $arrayOfAccountCreationErrors);
    }
}

// finish off the HTML for this page:
require_once "footer.php";

?>