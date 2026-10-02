<?php

session_start();

include "includes/database_connect.php";


/* =====================================
   CHECK LOGIN
===================================== */

if (!isset($_SESSION["user_id"])) {

    echo "<script>";
    echo "alert('Please login to view your profile.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";

    exit;
}


/* =====================================
   GET USER ID
===================================== */

$user_id = $_SESSION["user_id"];


/* =====================================
   FETCH USER DETAILS
===================================== */

$user_sql = "SELECT * FROM users WHERE id = '$user_id'";

$user_result = mysqli_query($con, $user_sql);

if (!$user_result) {
    die("User query failed: " . mysqli_error($con));
}

$user = mysqli_fetch_assoc($user_result);


/* =====================================
   FETCH INTERESTED PROPERTIES
===================================== */

$property_sql = "
    SELECT properties.*
    FROM properties
    INNER JOIN interested_users
    ON properties.id = interested_users.property_id
    WHERE interested_users.user_id = '$user_id'
";

$property_result = mysqli_query($con, $property_sql);

if (!$property_result) {
    die("Property query failed: " . mysqli_error($con));
}

?>


<!DOCTYPE html>
<html lang="en">


<head>

    <title>My Profile - PGLife</title>

    <?php include "includes/head_links.php"; ?>


    <style>

        /* =====================================
           PAGE
        ===================================== */

        body {
            background-color: #f5f5f5;
        }


        .dashboard-container {
            padding-top: 35px;
            padding-bottom: 50px;
        }


        /* =====================================
           PROFILE TITLE
        ===================================== */

        .profile-title {
            font-size: 30px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 25px;
        }


        /* =====================================
           PROFILE CARD
        ===================================== */

        .profile-card {
            background-color: white;
            border: 1px solid #dddddd;
            border-radius: 4px;
            padding: 30px;
            margin-bottom: 40px;
        }


        .profile-top {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }


        /* =====================================
           PROFILE IMAGE
        ===================================== */

        .profile-image-wrapper {
            text-align: center;
            margin-right: 25px;
        }


        .profile-image {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background-color: #f8f1e3;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }


        .profile-image i {
            font-size: 45px;
            color: #51c6c6;
        }


        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .add-photo {
            display: block;
            margin-top: 8px;
            font-size: 13px;
            color: #51c6c6;
        }


        /* =====================================
           PROFILE NAME
        ===================================== */

        .profile-name {
            font-size: 25px;
            font-weight: 600;
            color: #333333;
        }


        /* =====================================
           PROFILE INFORMATION
        ===================================== */

        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 50px;
            row-gap: 20px;
        }


        .profile-info-item {
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 12px;
        }


        .profile-info-label {
            font-size: 13px;
            color: #888888;
            margin-bottom: 5px;
        }


        .profile-info-value {
            font-size: 15px;
            color: #333333;
        }


        /* =====================================
           EDIT PROFILE BUTTON
        ===================================== */

        .edit-profile-btn {
            display: inline-block;
            margin-top: 25px;
            background-color: #51c6c6;
            border: 1px solid #51c6c6;
            color: white;
            padding: 9px 22px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
        }


        .edit-profile-btn:hover {
            background-color: #43b5b5;
            border-color: #43b5b5;
            color: white;
            text-decoration: none;
        }


        /* =====================================
           INTERESTED PROPERTIES TITLE
        ===================================== */

        .interested-title {
            font-size: 26px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 25px;
        }


        /* =====================================
           INTERESTED PROPERTIES
        ===================================== */

        .interested-properties {
            display: flex;
            flex-direction: column;
        }


        /* =====================================
           PROPERTY CARD
        Same style as property_list.php
        ===================================== */

        .property-card {
            background-color: white;
            border: 1px solid #dddddd;
            border-radius: 4px;
            margin-bottom: 25px;
            padding: 15px;
        }


        /* =====================================
           PROPERTY IMAGE
        ===================================== */

        .property-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }


        /* =====================================
           PROPERTY NAME
        ===================================== */

        .property-name {
            font-size: 20px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
        }


        /* =====================================
           PROPERTY LOCATION
        ===================================== */

        .property-location {
            color: #777777;
            font-size: 14px;
            margin-bottom: 15px;
        }


        .property-location i {
            margin-right: 5px;
        }


        /* =====================================
           PRICE
        ===================================== */

        .property-price {
            font-size: 18px;
            font-weight: 600;
            color: #333333;
        }


        .property-price span {
            font-size: 13px;
            font-weight: normal;
            color: #777777;
        }


        /* =====================================
           GENDER
        ===================================== */

        .property-gender {
            font-size: 14px;
            color: #777777;
            margin-top: 10px;
        }


        /* =====================================
           RATING
        ===================================== */

        .property-rating {
            margin-top: 12px;
            color: #f5a623;
        }


        /* =====================================
           INTERESTED HEART
        ===================================== */

        .interested-section {
            text-align: center;
            margin-top: 0;
            padding-top: 5px;
        }


        .interested-icon {
            font-size: 25px;
            color: #e74c3c;
            cursor: pointer;
        }


        .interested-icon:hover {
            color: #e74c3c;
        }


        .interested-text {
            display: block;
            font-size: 12px;
            color: #777777;
            margin-top: 3px;
        }


        /* =====================================
           VIEW BUTTON
        ===================================== */

        .details-button {
            display: block;
            width: 100%;
            max-width: 110px;
            margin: 0 auto;
            background-color: #51c6c6;
            border-color: #51c6c6;
            color: white;
            padding: 8px 10px;
            font-size: 14px;
            box-sizing: border-box;
            white-space: nowrap;
        }


        .details-button:hover {
            background-color: #43b5b5;
            border-color: #43b5b5;
            color: white;
        }


        /* =====================================
           ACTION COLUMN
        ===================================== */

        .property-action-column {
            min-width: 0;
            overflow: hidden;
        }


        /* =====================================
           NO INTERESTED PROPERTIES
        ===================================== */

        .no-properties {
            background-color: white;
            border: 1px solid #dddddd;
            border-radius: 4px;
            padding: 30px;
            color: #777777;
            text-align: center;
        }


        /* =====================================
           RESPONSIVE
        ===================================== */

        @media (max-width: 767px) {

            .profile-card {
                padding: 20px;
            }


            .profile-top {
                display: block;
                text-align: center;
            }


            .profile-image-wrapper {
                margin-right: 0;
                margin-bottom: 15px;
            }


            .profile-info {
                grid-template-columns: 1fr;
            }


            .property-image {
                height: 200px;
                margin-bottom: 15px;
            }


            .property-action-column {
                margin-top: 15px;
            }

        }


    </style>

</head>


<body>


<?php include "includes/header.php"; ?>


<!-- =====================================
     MAIN CONTAINER
===================================== -->

<div class="container dashboard-container">


    <!-- =====================================
         MY PROFILE
    ====================================== -->

    <div class="profile-title">

        My Profile

    </div>


    <div class="profile-card">


        <!-- Profile Top -->

        <div class="profile-top">


            <div class="profile-image-wrapper">


                <div class="profile-image">


                    <?php if (!empty($user["profile_image"])) { ?>


                        <img
                            src="<?php echo $user["profile_image"]; ?>"
                            alt="Profile Image"
                        >


                    <?php } else { ?>


                        <i class="fas fa-user"></i>


                    <?php } ?>


                </div>


                <span class="add-photo">

                    Add Photo

                </span>


            </div>


            <div class="profile-name">

                <?php echo htmlspecialchars($user["full_name"]); ?>

            </div>


        </div>


        <!-- Profile Information -->

        <div class="profile-info">


            <!-- Email -->

            <div class="profile-info-item">

                <div class="profile-info-label">

                    Email

                </div>

                <div class="profile-info-value">

                    <?php echo htmlspecialchars($user["email"]); ?>

                </div>

            </div>


            <!-- Phone -->

            <div class="profile-info-item">

                <div class="profile-info-label">

                    Phone

                </div>

                <div class="profile-info-value">

                    <?php

                    echo !empty($user["phone"])
                        ? htmlspecialchars($user["phone"])
                        : "Not added";

                    ?>

                </div>

            </div>


            <!-- College -->

            <div class="profile-info-item">

                <div class="profile-info-label">

                    College

                </div>

                <div class="profile-info-value">

                    <?php

                    echo !empty($user["college_name"])
                        ? htmlspecialchars($user["college_name"])
                        : "Not added";

                    ?>

                </div>

            </div>


            <!-- Gender -->

            <div class="profile-info-item">

                <div class="profile-info-label">

                    Gender

                </div>

                <div class="profile-info-value">

                    <?php

                    echo !empty($user["gender"])
                        ? htmlspecialchars(ucfirst($user["gender"]))
                        : "Not added";

                    ?>

                </div>

            </div>


            <!-- Address -->

            <div class="profile-info-item">

                <div class="profile-info-label">

                    Address

                </div>

                <div class="profile-info-value">

                    <?php

                    echo !empty($user["address"])
                        ? htmlspecialchars($user["address"])
                        : "Not added";

                    ?>

                </div>

            </div>


        </div>


        <!-- Edit Profile -->

        <a
            href="edit_profile.php"
            class="edit-profile-btn"
        >
            Edit Profile
        </a>


    </div>


    <!-- =====================================
         INTERESTED PROPERTIES
    ====================================== -->

    <div class="interested-title">

        Interested Properties

    </div>


    <?php if (mysqli_num_rows($property_result) > 0) { ?>


        <div class="interested-properties">


            <?php while ($property = mysqli_fetch_assoc($property_result)) {


                $property_id = $property["id"];


                /* Find property images */

                $image_folder = "img/properties/" . $property_id . "/";

                $images = glob(
                    $image_folder . "*.{jpg,jpeg,png}",
                    GLOB_BRACE
                );


            ?>


                <!-- =====================================
                     PROPERTY CARD
                ====================================== -->

                <div class="property-card">


                    <div class="row align-items-center">


                        <!-- Property Image -->

                        <div class="col-md-4">


                            <?php if (count($images) > 0) { ?>


                                <img
                                    src="<?php echo $images[0]; ?>"
                                    class="property-image"
                                    alt="<?php echo htmlspecialchars($property["name"]); ?>"
                                >


                            <?php } ?>


                        </div>


                        <!-- Property Information -->

                        <div class="col-md-5">


                            <!-- Property Name -->

                            <div class="property-name">

                                <?php echo htmlspecialchars($property["name"]); ?>

                            </div>


                            <!-- Location -->

                            <div class="property-location">

                                <i class="fas fa-map-marker-alt"></i>

                                <?php echo htmlspecialchars($property["city"]); ?>

                            </div>


                            <!-- Price -->

                            <div class="property-price">

                                ₹<?php echo $property["price"]; ?>

                                <span>

                                    /month

                                </span>

                            </div>


                            <!-- Gender -->

                            <div class="property-gender">

                                <i class="fas fa-user"></i>

                                <?php echo htmlspecialchars(ucfirst($property["gender"])); ?>

                            </div>


                            <!-- Rating -->

                            <div class="property-rating">


                                <?php

                                $rating = $property["rating"];


                                for ($i = 1; $i <= 5; $i++) {


                                    if ($i <= $rating) {


                                        echo '<i class="fas fa-star"></i>';


                                    } else {


                                        echo '<i class="far fa-star"></i>';


                                    }


                                }


                                ?>


                                <span class="ml-2">

                                    <?php echo $rating; ?>/5

                                </span>


                            </div>


                        </div>


                        <!-- Interested + View -->

                        <div class="col-md-3 text-center property-action-column">


                            <!-- Interested -->

                            <div class="interested-section">


                                <i
                                    class="fas fa-heart interested-icon"
                                    onclick="removeInterested(<?php echo $property_id; ?>, this)"
                                ></i>


                                <span class="interested-text">

                                    Interested

                                </span>


                            </div>


                            <!-- View -->

                            <div class="mt-4">


                                <a
                                    href="property_detail.php?id=<?php echo $property_id; ?>"
                                    class="btn details-button"
                                >

                                    View

                                </a>


                            </div>


                        </div>


                    </div>


                </div>


            <?php } ?>


        </div>


    <?php } else { ?>


        <div class="no-properties">

            You have not marked any property as interested yet.

        </div>


    <?php } ?>


</div>


<?php include "includes/footer.php"; ?>


<!-- =====================================
     JAVASCRIPT
===================================== -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>


<script>


function removeInterested(propertyId, heart) {


    $.ajax({

        url: "api/toggle_interested.php",

        type: "POST",

        data: {
            property_id: propertyId
        },


        success: function(response) {


            response = response.trim();


            if (response == "removed") {


                $(heart)
                    .closest(".property-card")
                    .fadeOut(300, function() {

                        $(this).remove();

                    });


            }


            else if (response == "login") {


                alert("Please login to mark a property as interested.");


            }


            else {


                alert("Something went wrong. Please try again.");


            }


        },


        error: function() {

            alert("Something went wrong. Please try again.");

        }

    });


}


</script>


</body>

</html>