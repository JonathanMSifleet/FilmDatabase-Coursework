<?php

// function to sanitise (clean) user data:
function sanitise($str, $connection) {
    if (get_magic_quotes_gpc()) {
        // just in case server is running an old version of PHP with "magic quotes" running:
        $str = stripslashes($str);
    }

    // ' is a delimiter, replace with a quotation mark:
    $str = str_replace("'", "’", $str);

    // escape any dangerous characters, e.g. quotes:
    $str = mysqli_real_escape_string($connection, $str);
    // ensure any html code is safe by converting reserved characters to entities:
    $str = htmlentities($str);
    // return the cleaned string:
    return $str;
}

// sanitise user inputs when they are creating their account:
function sanitiseUserData($connection, &$username, &$email, &$password, &$firstname, &$surname) {
    $username = sanitise($_POST['username'], $connection);
    $email = sanitise($_POST['email'], $connection);
    $password = sanitise($_POST['password'], $connection);
    $firstname = sanitise($_POST['firstname'], $connection);
    $surname = sanitise($_POST['surname'], $connection);
}

// displays the account creation form:
function displayCreateAccountForm($username, $email, $password, $firstname, $surname, $arrayOfAccountErrors) {

    $currentURL = $_SERVER['REQUEST_URI'];

    // form to create account:
    echo <<<_END
            <form action="$currentURL" method="post">
              Please fill in the following fields:<br>
              Username: <input type="text" name="username" minlength="3" maxlength="20" value="$username" required> $arrayOfAccountErrors[0]
              <br>
              Email: <input type="email" name="email" minlength="3" maxlength="64" value="$email" required> $arrayOfAccountErrors[1]
              <br>
              Password: <input type="password" name="password" maxlength="32" value="$password"> Leave blank for an auto-generated password $arrayOfAccountErrors[2]
              <br>
              First name: <input type="text" name="firstname" minlength="2" maxlength="16" value="$firstname" required> $arrayOfAccountErrors[3]
              <br>
              Surname: <input type="text" name="surname" minlength="2" maxlength="24" value="$surname" required> $arrayOfAccountErrors[4]
              <br>
              <input type="submit" value="Submit">
            </form>
_END;
}

// inserts new account into database:
function createAccount($connection, $username, $email, $password, $firstname, $surname, $arrayOfAccountCreationErrors) {
    $randomPasswordGenerated = false;
    $plaintextPassword = "";

    // if password length = 0, generate a random password
    if (strlen($password) == 0) {
        $randomPasswordGenerated = true;
        $password = generateAlphanumericString();
        $plaintextPassword = $password;
    }

    // creates an array of account errors
    createArrayOfAccountErrors($username, $email, $password, $firstname, $surname, $arrayOfAccountCreationErrors);

    // concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
    $errors = implode('', $arrayOfAccountCreationErrors);

    // check that all the validation tests passed before inserting information into the database:
    if ($errors == "") {

        $password = encryptInput($password);

        // try to insert the new details:
        $query = "INSERT INTO users (username, firstname, surname, password, email) VALUES ('$username','$firstname','$surname','$password','$email')";
        $result = mysqli_query($connection, $query);

        // if no data returned, we set result to true(success)/false(failure):
        if ($result) {
            // show a successful signup message:
            echo "Account creation was successful<br><br>";

            if ($randomPasswordGenerated) {
                echo "Your password is: " . $plaintextPassword . "<br><br>";
            }
            echo "<a href = sign_in.php>Click here to sign in</a><br>";
        } else {
            displayCreateAccountForm($username, $email, $password, $firstname, $surname, $arrayOfAccountCreationErrors);
            echo mysqli_error($connection) . "<br>";
        }
    } else {
        // validation failed, show the form again with guidance:
        displayCreateAccountForm($username, $email, $password, $firstname, $surname, $arrayOfAccountCreationErrors);
        // show an unsuccessful sign up message:

        echo mysqli_error($connection) . "<br>";

        echo "Account creation failed, please check the errors shown above and try again<br>";
    }
}

// initialises every element in an array with a null value
function initEmptyArray(&$array, $size) {
    for ($i = 0; $i <= $size; $i++) {
        $array[$i] = "";
    }
}

// this function validates all user inputs, and adds each validation message to an array of errors
function createArrayOfAccountErrors($username, $email, $password, $firstname, $surname, &$arrayOfErrors) {
    $arrayOfErrors[0] = validateStringLength($username, 1, 20);
    $arrayOfErrors[1] = validateEmail($email, 1, 64);
    $arrayOfErrors[2] = validatePassword($password, 12, 32);
    $arrayOfErrors[3] = validateName($firstname, 2, 16); // see line below +
    $arrayOfErrors[4] = validateName($surname, 2, 20); // shortest last name I've ever seen was a girl called "Ng" +
}

// if the data is valid return an empty string, if the data is invalid return a help message
function validateStringLength($field, $minlength, $maxlength) // edit function name
{
    // echo "String length: " . strlen($field);
    if (strlen($field) < $minlength) {
        // wasn't a valid length, return a help message:
        return "Input length: " . strlen($field) . ", minimum length: " . $minlength;
    } elseif (strlen($field) > $maxlength) {
        // wasn't a valid length, return a help message:
        return "Input length: " . strlen($field) . ", maximum length: " . $maxlength;
    } else {
        // data was valid, return an empty string:
        return "";
    }
}

// if the input is contains only non-numbers and is the correct length then return an empty string, if the data is invalid return a help message
function validateName($field, $minlength, $maxlength) // master function +
{
    $errors = "";
    $errors = $errors . checkIsNonNumeric($field);
    $errors = $errors . validateStringLength($field, $minlength, $maxlength);
    return $errors;
}

// this function checks if an inputted email address is valid, and then returns an error message if it isn't
function validateEmail($field, $minLength, $maxLength) {
    $errors = "";
    $errors = $errors . validateStringLength($field, $minLength, $maxLength);
    $errors = $errors . checkIsEmail($field);
    return $errors;
}

// if password length = 0, generate a random password,
// otherwise check if password is correct length
function validatePassword($field, $minLength, $maxLength) {
    if (strlen($field) == 0) {
        return "Generate random password";
    } else {
        return validateStringLength($field, $minLength, $maxLength);
    }
}

// if the input contains the @ symbol then return an empty string, if the data is invalid return a help message
function checkIsEmail($field) {
    if (strpos($field, '@') == false) {
        return "Email must contain an '@'";
    } else {
        return "";
    }
}

// if the input is contains only numbers then return an empty string, if the data is invalid return a help message
function checkIsNonNumeric($field) {
    $charArray = str_split($field);
    $lengthOfCharArray = count($charArray);

    for ($i = 0; $i < $lengthOfCharArray; $i++) {
        if (is_numeric($charArray[$i]) == true) {
            return "Must not contain any numbers ";
        }
    }
    return "";
}

// this function encrypts a user input
function encryptInput($input) {
    return password_hash($input, PASSWORD_BCRYPT); // leave third parameter empty to generate random salt every time
}

?>