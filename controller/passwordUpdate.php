<?php
session_start();
include "../database/env.php";
$oldPsk = $_REQUEST['oldPsk'];
$psk = $_REQUEST['psk'];
$confirmPsk = $_REQUEST['confirmPsk'];
$dbPsk = $_SESSION['auth']['password'];
$isPasswordTrue = password_verify($oldPsk, $dbPsk);
$id = $_SESSION['auth']['id'];
$errors = [];


if(!$isPasswordTrue){
    $errors['oldPsk'] = 'Your old password did not match!';
    $_SESSION['errors'] =  $errors;
    header("Location: ../backend/profile.php");
} else{

    if(empty($psk)){
        $errors['psk'] = 'new password required';
    }
    if($psk != $confirmPsk){
        $errors['psk'] = 'new password and confirm password did not match!';
    }


    if(count($errors) > 0){
        $_SESSION['errors'] =  $errors;
        header("Location: ../backend/profile.php");
    }else {
        //* PROCEED
        $encPassword = password_hash($psk,PASSWORD_BCRYPT);
        $query = "UPDATE users SET password='$encPassword' WHERE id='$id'";
        $res = mysqli_query($conn, $query);
        if($res){
            $_SESSION['auth']['password'] = $encPassword;
            header("Location: ../backend/profile.php");
        }


    }



}