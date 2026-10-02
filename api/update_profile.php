<?php

session_start();

include "../includes/database_connect.php";


/* =====================================
   CHECK LOGIN
===================================== */

if (!isset($_SESSION["user_id"])) {

    echo "<script>";
    echo "alert('Please login first.');";
    echo "window.location.href = '../index.php';";
    echo "</script>";

    exit;
}


/* =====================================
   GET USER ID
===================================== */

$user_id = $_SESSION["user_id"];


/* =====================================
   GET FORM DATA
===================================== */

$full_name = mysqli_real_escape_string(
    $con,
    $_POST["full_name"]
);

$phone = mysqli_real_escape_string(
    $con,
    $_POST["phone"]
);

$email = mysqli_real_escape_string(
    $con,
    $_POST["email"]
);

$college_name = mysqli_real_escape_string(
    $con,
    $_POST["college_name"]
);

$gender = mysqli_real_escape_string(
    $con,
    $_POST["gender"]
);

$address = mysqli_real_escape_string(
    $con,
    $_POST["address"]
);


/* =====================================
   PROFILE IMAGE
===================================== */

$profile_image_sql = "";


if (
    isset($_FILES["profile_image"]) &&
    $_FILES["profile_image"]["error"] == 0
) {


    $allowed_types = [
        "image/jpeg",
        "image/png"
    ];


    $file_type = $_FILES["profile_image"]["type"];


    if (!in_array($file_type, $allowed_types)) {

        echo "<script>";
        echo "alert('Only JPG and PNG images are allowed.');";
        echo "window.location.href = '../edit_profile.php';";
        echo "</script>";

        exit;

    }


    $extension = pathinfo(
        $_FILES["profile_image"]["name"],
        PATHINFO_EXTENSION
    );


    $file_name = "user_" . $user_id . "_" . time() . "." . $extension;


    $upload_folder = "../img/users/";


    $upload_path = $upload_folder . $file_name;


    if (move_uploaded_file(
        $_FILES["profile_image"]["tmp_name"],
        $upload_path
    )) {

        $profile_image =
            "img/users/" . $file_name;


        $profile_image =
            mysqli_real_escape_string(
                $con,
                $profile_image
            );


        $profile_image_sql =
            ", profile_image = '$profile_image'";

    }

}


/* =====================================
   UPDATE USER
===================================== */

$sql = "
    UPDATE users
    SET
        full_name = '$full_name',
        phone = '$phone',
        email = '$email',
        college_name = '$college_name',
        gender = '$gender',
        address = '$address'
        $profile_image_sql
    WHERE id = '$user_id'
";


$result = mysqli_query($con, $sql);


/* =====================================
   SUCCESS
===================================== */

if ($result) {


    $_SESSION["full_name"] = $full_name;


    echo "<script>";

    echo "alert('Profile updated successfully!');";

    echo "window.location.href = '../dashboard.php';";

    echo "</script>";


} else {


    echo "<script>";

    echo "alert('Profile update failed. Please try again.');";

    echo "window.location.href = '../edit_profile.php';";

    echo "</script>";

}

?>