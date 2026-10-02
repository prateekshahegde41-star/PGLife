<?php

session_start();

include "../includes/database_connect.php";


/* Check login */

if (!isset($_SESSION["user_id"])) {

    echo "<script>";
    echo "alert('Please login before booking.');";
    echo "window.location.href = '../index.php';";
    echo "</script>";

    exit;
}


/* Get form values */

$user_id = $_SESSION["user_id"];

$property_id = $_POST["property_id"];

$full_name = $_POST["full_name"];

$phone = $_POST["phone"];

$move_in_date = $_POST["move_in_date"];

$duration = $_POST["duration"];


/* Insert booking */

$sql = "INSERT INTO bookings
        (user_id, property_id, full_name, phone, move_in_date, duration)
        VALUES
        ('$user_id', '$property_id', '$full_name', '$phone', '$move_in_date', '$duration')";


$result = mysqli_query($con, $sql);


if ($result) {

    echo "<script>";

    echo "alert('Booking submitted successfully!');";

    echo "window.location.href = '../index.php';";

    echo "</script>";

} else {

    echo "<script>";

    echo "alert('Booking failed. Please try again.');";

    echo "window.history.back();";

    echo "</script>";

}

?>