<?php

session_start();

include "../includes/database_connect.php";


/* Check if user is logged in */

if (!isset($_SESSION["user_id"])) {

    echo "login";
    exit;

}


$user_id = $_SESSION["user_id"];

$property_id = $_POST["property_id"];


/* Check if property is already interested */

$sql = "SELECT * FROM interested_users
        WHERE user_id = '$user_id'
        AND property_id = '$property_id'";

$result = mysqli_query($con, $sql);


/* If already interested → remove */

if (mysqli_num_rows($result) > 0) {

    $sql = "DELETE FROM interested_users
            WHERE user_id = '$user_id'
            AND property_id = '$property_id'";

    mysqli_query($con, $sql);

    echo "removed";

}


/* If not interested → add */

else {

    $sql = "INSERT INTO interested_users
            (user_id, property_id)
            VALUES
            ('$user_id', '$property_id')";

    mysqli_query($con, $sql);

    echo "added";

}

?>