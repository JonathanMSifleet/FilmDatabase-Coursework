<?php

require_once "header.php";

// main page

echo "<p>Home page</p>";

echo <<<_END

<ul>
<li><a href = sign_in.php>Register or login</li>
<li><a href = create_database.php> create database </a></li>
</ul>

_END;


?>