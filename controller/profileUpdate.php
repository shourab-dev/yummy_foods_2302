<?php
include "../database/env.php";
//* DATA COLLECT
session_start();
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$email = $_REQUEST['email'];
$profileImage = $_FILES['profileImage'];
$extension = null;
if ($profileImage['size'] > 0) {
    $extension = pathinfo($profileImage['name'])['extension'];
}
$accecptedTypes = ['jpg', 'png'];
$userId = $_SESSION['auth']['id'];

// echo "<pre>";
// print_r($profileImage);
// echo "</pre>";
// exit();


$errors = [];

//* VALIDATION
// if($profileImage['size'] == 0) {
//     $errors['profileImage'] = 'Please select an image';
// } else if(!in_array($extension, $accecptedTypes)){
//     $errors['profileImage'] = 'Please select an image with the extension of jpg or png';
// }



//* IF ERRORS FOUND
if (count($errors) > 0) {
    //* REDIRECT BACK
    $_SESSION['errors'] = $errors;
    header("location: ../backend/profile.php");
}

//* IF NO ERROR FOUND

else {
    //* IF UPLOAD FOLDER NOT FOUND
    $path = '../uploads';
    if (!file_exists($path)) {
        mkdir($path);
    }


    //*  FILE UPLOAD

    if ($profileImage['size'] > 0) {
        //* CHECK FOR PREV FILE
        if(file_exists($path . '/' . $_SESSION['auth']['profile_image'])){
           unlink($path . '/' . $_SESSION['auth']['profile_image']);
        } 

        $fileName = 'user-' . uniqid() . ".$extension";
        $from = $profileImage['tmp_name'];
        $to = $path . "/$fileName";

        $isUploaded = move_uploaded_file($from, $to);
        $query = "UPDATE users SET fname='$fname',lname='$lname',email='$email',profile_image='$fileName' WHERE id = '$userId'";
        $res  = mysqli_query($conn, $query);
    } else {

        $query = "UPDATE users SET fname='$fname',lname='$lname',email='$email' WHERE id = '$userId'";
        $res  = mysqli_query($conn, $query);
    }

    if ($res) {
        $_SESSION['auth']['fname'] = $fname;
        $_SESSION['auth']['lname'] = $lname;
        $_SESSION['auth']['email'] = $email;

        if($profileImage['size'] > 0){
            $_SESSION['auth']['profile_image'] = $fileName;
        }

        header("Location: ../backend/profile.php");
    }
}
