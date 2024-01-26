<?php
session_start();
include "../database/env.php";

//* GRAP DATA
$firstName = $_REQUEST['fname'];
$lastName = $_REQUEST['lname'];
$email = $_REQUEST['email'];
$password = $_REQUEST['password'];
$confirmPassword = $_REQUEST['confirmPassword'];




//* VALIDATION
$errors = [];

//* TITLE ERROR
if (empty($firstName)) {
    $errors['name'] = "your first name is missing";
}

//* ERROR ERROR
if (empty($email)) {
    $errors['email'] = "your email address is missing";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "your email address is invalid";
}


//* PASSWORD ERROR


if (empty($password)) {
    $errors['password'] = "your password is missing";
} else if (strlen($password) < 8) {
    $errors['password'] = "your password has to be greater than 8 digit";
}


//* CONFIRM PASSWORD ERROR

if (empty($confirmPassword)) {
    $errors['confirmPassword'] = "your repeat password is missing";
} else if ($password != $confirmPassword) {
    $errors['confirmPassword'] = "Your password did not match!";
}


//* IF ERROR FOUND
if( count($errors) > 0 ){

    $_SESSION['errors'] = $errors;
    header('Location: ../backend/register.php');

} else {
    //* REGISTER 
    $encPass = password_hash($password,PASSWORD_BCRYPT);
    
    $query = "INSERT INTO users(fname, lname, email, password) VALUES ('$firstName', '$lastName', '$email', '$encPass')";

    $response = mysqli_query($conn, $query);
    //* SUCCESFULLY REGISTER
    if($response){
        header('Location: ../backend/login.php');
    }

    
}