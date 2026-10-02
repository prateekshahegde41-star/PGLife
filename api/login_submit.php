<?php

session_start();

include "../includes/database_connect.php";

$email = $_POST["email"];
$password = $_POST["password"];


/* Find user */

$sql = "SELECT * FROM users WHERE email = '$email'";

$result = mysqli_query($con, $sql);


/* Check if user exists */

if (mysqli_num_rows($result) == 0) {

    echo "<script>";
    echo "alert('Invalid email or password.');";
    echo "window.location.href = '../index.php';";
    echo "</script>";

    exit;
}


/* Get user details */

$user = mysqli_fetch_assoc($result);


/* Check password */

if (password_verify($password, $user["password"])) {

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["full_name"] = $user["full_name"];

    echo "<script>";
    echo "alert('Login successful!');";
    echo "window.location.href = '../index.php';";
    echo "</script>";

} else {

    echo "<script>";
    echo "alert('Invalid email or password.');";
    echo "window.location.href = '../index.php';";
    echo "</script>";

}

?>