<?php

include "../includes/database_connect.php";

$full_name = $_POST["full_name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$college_name = $_POST["college_name"];
$gender = $_POST["gender"];


/* Check if email already exists */

$sql = "SELECT * FROM users WHERE email = '$email'";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {

    echo "Email already registered.";
    exit;

}


/* Encrypt password */

$password = password_hash($password, PASSWORD_DEFAULT);


/* Insert user */

$sql = "INSERT INTO users
        (full_name, phone, email, password, college_name, gender)
        VALUES
        ('$full_name', '$phone', '$email', '$password', '$college_name', '$gender')";


if (mysqli_query($con, $sql)) {

    echo "<script>";

    echo "alert('Signup successful!');";

    echo "window.location.href = '../index.php';";

    echo "</script>";

    exit;

} else {

    echo "Signup failed.";

}

?>