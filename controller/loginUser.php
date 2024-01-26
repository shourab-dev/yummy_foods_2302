<?php
session_start();
include_once "../database/env.php";

$email = $_REQUEST['email'];
$password = $_REQUEST['password'];
$errors = [];

//* VALIDATION
//* VALIDATION ENDS


//* EMAIL CHECKS
$query = "SELECT * FROM users WHERE email='$email'";
$res = mysqli_query($conn,$query);
//* EMAIL NOT FOUND
if($res->num_rows == 0){
    $errors['email'] = "Your Email Address is incorrect";
    $_SESSION['errors'] = $errors;
    header("Location: ../backend/login.php");
} else {
    $dbUser = mysqli_fetch_assoc($res);
    $isPasswordTrue = password_verify($password,$dbUser['password']);
    
    //* IF PASSWORD IS INNCORRECT
    if(!$isPasswordTrue){
        $errors['password'] = "Your password is incorrect";
        $_SESSION['errors'] = $errors;
        header("Location: ../backend/login.php");
    } else 
    {
        //* redirect to dashboard

        $_SESSION['auth'] = $dbUser;


        header("Location: ../backend/dashboard.php");
    }
    
}