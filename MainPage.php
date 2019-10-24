<?php

// code for getting image from TMDB:

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.themoviedb.org/3/movie/[TMDBID]/images?language=en-US&api_key=8058858125889a818b0e1831a9c045d0",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "{}",
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>

    <html>
    <title> Assessment </title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- imports third party font -->
    <link rel="stylesheet" href="mystyle.css">
    <!-- import style sheet -->
    <html lang="en" dir="LTR">

    <head>
    </head>

    <header>
    </header>

    <nav>
        <!-- nav bar -->
        <ul>
            <li>
            </li>
        </ul>
    </nav>

    <body>
        <!-- signifies body of the page -->

        <footer>
        </footer>

    </html>
