<?php

session_start();

include "includes/database_connect.php";

$property_id = $_GET["id"];


/* Fetch property details */

$sql = "SELECT * FROM properties WHERE id = $property_id";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) == 0) {

    echo "Property not found.";
    exit();

}

$property = mysqli_fetch_assoc($result);

/* Check whether this property is already interested */

$is_interested = false;

if (isset($_SESSION["user_id"])) {

    $user_id = $_SESSION["user_id"];

    $interest_sql = "
        SELECT property_id
        FROM interested_users
        WHERE user_id = '$user_id'
        AND property_id = '$property_id'
    ";

    $interest_result = mysqli_query($con, $interest_sql);

    if (mysqli_num_rows($interest_result) > 0) {

        $is_interested = true;

    }

}

/* Fetch amenities for this property */

$amenity_sql = "SELECT amenities.name
                FROM amenities
                INNER JOIN property_amenities
                ON amenities.id = property_amenities.amenity_id
                WHERE property_amenities.property_id = $property_id";

$amenity_result = mysqli_query($con, $amenity_sql);

if (!$amenity_result) {
    die("Amenity query failed: " . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>
        <?php echo $property["name"]; ?> - PGLife
    </title>

    <?php include "includes/head_links.php"; ?>


    <style>

        body {
            background-color: #f7f7f7;
        }


        /* Main container */

        .property-detail-container {
            margin-top: 40px;
            margin-bottom: 50px;
        }


        /* Main card */

        .property-detail-card {
            background-color: white;
            border-radius: 5px;
            border: 1px solid #ddd;
            overflow: hidden;
        }


        /* =====================================
           IMAGE CAROUSEL
        ===================================== */

        .property-gallery {
            width: 100%;
        }


        .property-gallery .carousel-item {
            height: 420px;
        }


        .property-gallery .property-image {
            width: 100%;
            height: 420px;
            object-fit: cover;
            display: block;
        }


        /* =====================================
           PROPERTY INFORMATION
        ===================================== */

        .property-info-section {
            padding: 25px;
        }


        .property-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }


        .property-city {
            color: #777;
            font-size: 15px;
            margin-bottom: 25px;
        }


        .property-info {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }


        .info-box {
            margin-right: 50px;
            margin-bottom: 15px;
        }


        .info-title {
            font-size: 13px;
            color: #888;
            margin-bottom: 3px;
        }


        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }


        /* Rating */

        .rating {
            color: #f5a623;
            font-size: 18px;
        }


        /* Interested */

        .interested-section {
            text-align: center;
            width: 80px;
            margin-bottom: 15px;
        }


        .interested-icon {
            font-size: 25px;
            color: #999;
            cursor: pointer;
        }


        .interested-icon:hover {
            color: #e74c3c;
        }


        .interested-text {
            display: block;
            font-size: 12px;
            color: #777;
            margin-top: 3px;
        }


        /* =====================================
           SECTION TITLES
        ===================================== */

        .section-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-top: 35px;
            margin-bottom: 20px;
            padding-left: 25px;
        }


        /* =====================================
           AMENITIES
        ===================================== */

        .amenity-container {
            padding: 0 25px;
        }

        .amenities-container {
            display: flex;
            justify-content: center;
            gap: 70px;
            margin: 0;
            padding: 0 30px;
        }

        .amenities-section {
            background-color: #fef8f2;
            padding: 20px 30px 25px;
            margin-top: 35px;
            border-radius: 6px;
        }

        .amenities-section .section-title {
            margin-top: 0;
            margin-bottom: 20px;
        }
        .amenity-column {
            width: 180px;
        }

        .amenity-column-title {
            font-size: 17px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #555;
            font-size: 14px;
        }

        .amenity-item i {
            width: 25px;
            margin-right: 8px;
            color: #51c6c6;
            font-size: 16px;
        }

        .amenity-column-title {
            font-size: 17px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #555;
            font-size: 14px;
        }

        .amenity-item i {
            width: 25px;
            margin-right: 8px;
            color: #51c6c6;
            font-size: 16px;
        }

        @media (max-width: 767px) {

            .amenities-container {
                flex-wrap: wrap;
            }

            .amenity-column {
                width: 50%;
                margin-bottom: 20px;
            }

        }

        @media (max-width: 480px) {

            .amenity-column {
                width: 100%;
            }

        }

        /* =====================================
           PROPERTY DETAILS
        ===================================== */

        .details-container {
            padding: 0 25px;
        }


        .details-box {
            background-color: white;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 4px;
        }


        .details-box p {
            margin-bottom: 10px;
            color: #555;
        }


        .details-box p:last-child {
            margin-bottom: 0;
        }

        /* =====================================
        RATINGS & REVIEWS
        ===================================== */

        .ratings-section {
            margin-top: 40px;
            padding: 0 25px 30px;
        }

        .ratings-content {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 30px;
        }

        .rating-breakdown {
            width: 45%;
        }

        .overall-rating {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background-color: #51c6c6;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-left: 70px;
        }

        .rating-breakdown {
            width: 55%;
        }

        .rating-row {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }

        .rating-label {
            width: 150px;
            font-size: 15px;
            color: #555;
        }

        .rating-stars {
            color: #f5a623;
            font-size: 18px;
        }

        .overall-rating {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background-color: #51c6c6;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .overall-rating-number {
            font-size: 38px;
            font-weight: 600;
        }

        .overall-rating-stars {
            font-size: 17px;
            margin-top: 4px;
        }

        .overall-rating-text {
            font-size: 13px;
            margin-top: 5px;
        }

        /* =====================================
        WHAT PEOPLE SAY
        ===================================== */

        .testimonials-section {
            margin-top: 20px;
            padding: 0 25px 40px;
        }

        .testimonials-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            max-width: 900px;
            margin: 0 auto;
        }

        .testimonial-card {
            position: relative;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 25px 30px 25px 85px;
            min-height: 130px;
        }

        .testimonial-avatar {
            position: absolute;
            left: -25px;
            top: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #51c6c6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
            border: 5px solid #f7f7f7;
        }

        .testimonial-quote {
            position: absolute;
            top: 18px;
            left: 55px;
            font-size: 45px;
            color: #51c6c6;
            font-weight: bold;
            line-height: 1;
        }

        .testimonial-text {
            color: #555;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 12px;
        }

        .testimonial-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }


        /* =====================================
           MOBILE RESPONSIVE
        ===================================== */

        @media (max-width: 767px) {


            .property-gallery .carousel-item {
                height: 260px;
            }


            .property-gallery .property-image {
                height: 260px;
            }


            .property-info-section {
                padding: 20px;
            }


            .property-title {
                font-size: 24px;
            }


            .property-info {
                display: block;
            }


            .info-box {
                margin-right: 0;
            }


            .interested-section {
                margin-top: 10px;
            }


            .section-title {
                padding-left: 20px;
            }


            .amenity-container,
            .details-container {
                padding: 0 20px;
            }

            .book-now-section {
                margin-left: 0;
                margin-top: 10px;
            }

            .ratings-section {
                padding: 0 20px 25px;
            }

            .ratings-content {
                display: block;
                padding: 25px 20px;
            }

            .rating-breakdown {
                width: 100%;
            }

            .overall-rating {
                margin: 25px auto 0;
            }

             .testimonials-section {
                padding: 0 20px 30px;
            }

            .testimonial-card {
                padding: 25px 20px 20px 55px;
            }

            .testimonial-avatar {
                left: -10px;
                width: 50px;
                height: 50px;
                font-size: 16px;
            }

            .testimonial-quote {
                left: 38px;
                font-size: 35px;
            }

        }

        .book-now-section {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .book-now-btn {
            display: inline-block;
            background-color: #51c6c6;
            color: white;
            padding: 10px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
        }

        .book-now-btn:hover {
            color: white;
            text-decoration: none;
            background-color: #3fb1b1;
        }

    </style>

</head>


<body>


<?php include "includes/header.php"; ?>


<!-- Signup Modal -->

<?php include "includes/signup_modal.php"; ?>


<!-- Login Modal -->

<?php include "includes/login_modal.php"; ?>


<div class="container property-detail-container">


    <div class="property-detail-card">


        <!-- =====================================
             PROPERTY IMAGE CAROUSEL
        ====================================== -->

        <div class="property-gallery">

            <?php

            $image_folder = "img/properties/" . $property_id . "/";

            $images = glob(
                $image_folder . "*.{jpg,jpeg,png}",
                GLOB_BRACE
            );

            ?>


            <?php if (count($images) > 0) { ?>


                <div
                    id="propertyCarousel"
                    class="carousel slide"
                    data-ride="carousel"
                >


                    <div class="carousel-inner">


                        <?php

                        $image_index = 0;

                        foreach ($images as $image) {

                        ?>


                            <div
                                class="carousel-item
                                <?php echo ($image_index == 0) ? 'active' : ''; ?>"
                            >


                                <img
                                    src="<?php echo $image; ?>"
                                    class="property-image"
                                    alt="<?php echo $property["name"]; ?>"
                                >


                            </div>


                        <?php

                            $image_index++;

                        }

                        ?>


                    </div>


                    <?php if (count($images) > 1) { ?>


                        <!-- Previous button -->

                        <a
                            class="carousel-control-prev"
                            href="#propertyCarousel"
                            role="button"
                            data-slide="prev"
                        >

                            <span
                                class="carousel-control-prev-icon"
                                aria-hidden="true"
                            ></span>

                            <span class="sr-only">
                                Previous
                            </span>

                        </a>


                        <!-- Next button -->

                        <a
                            class="carousel-control-next"
                            href="#propertyCarousel"
                            role="button"
                            data-slide="next"
                        >

                            <span
                                class="carousel-control-next-icon"
                                aria-hidden="true"
                            ></span>

                            <span class="sr-only">
                                Next
                            </span>

                        </a>


                    <?php } ?>


                </div>


            <?php } else { ?>


                <img
                    src="img/default.jpg"
                    class="property-image"
                    alt="Property Image"
                >


            <?php } ?>


        </div>



        <!-- =====================================
             PROPERTY INFORMATION
        ====================================== -->

        <div class="property-info-section">


            <!-- Property Name -->

            <h1 class="property-title">

                <?php echo $property["name"]; ?>

            </h1>


            <!-- City -->

            <div class="property-city">

                <i class="fas fa-map-marker-alt"></i>

                <?php echo $property["city"]; ?>

            </div>


            <div class="property-info">


                <!-- Rent -->

                <div class="info-box">

                    <div class="info-title">
                        Rent
                    </div>

                    <div class="info-value">

                        ₹<?php echo $property["price"]; ?>/month

                    </div>

                </div>


                <!-- Gender -->

                <div class="info-box">

                    <div class="info-title">
                        Gender
                    </div>

                    <div class="info-value">

                        <?php echo ucfirst($property["gender"]); ?>

                    </div>

                </div>


                <!-- Rating -->

                <div class="info-box">

                    <div class="info-title">
                        Rating
                    </div>

                    <div class="rating">


                        <?php

                        $rating = $property["rating"];

                        echo $rating . " ";


                        for ($i = 1; $i <= 5; $i++) {


                            if ($i <= floor($rating)) {

                                echo '<i class="fas fa-star"></i>';

                            } else {

                                echo '<i class="far fa-star"></i>';

                            }

                        }

                        ?>


                    </div>

                </div>


                <!-- Interested -->

                <div class="interested-section">

                    <i
                        class="<?php echo $is_interested ? 'fas' : 'far'; ?> fa-heart interested-icon"
                        style="color: <?php echo $is_interested ? '#e74c3c' : '#999999'; ?>;"
                        onclick="toggleInterested(<?php echo $property_id; ?>, this)"
                    ></i>

                    <span class="interested-text">
                        Interested
                    </span>

                </div>

                <div class="book-now-section">

                    <a href="booking.php?id=<?php echo $property_id; ?>" class="book-now-btn">
                        Book Now
                    </a>

                </div>


            </div>


        </div>



        <!-- =====================================
             AMENITIES
        ====================================== -->

        <div class="amenities-section">

            <h2 class="section-title">
                Amenities
            </h2>

            <div class="amenities-container">

                <!-- Building -->

                <div class="amenity-column">

                    <div class="amenity-column-title">
                        Building
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-bolt"></i>
                        Power Backup
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-building"></i>
                        Lift
                    </div>

                </div>


                <!-- Common Area -->

                <div class="amenity-column">

                    <div class="amenity-column-title">
                        Common Area
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-wifi"></i>
                        Wi-Fi
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-tv"></i>
                        TV
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-tint"></i>
                        Water Purifier
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-utensils"></i>
                        Dining
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-tshirt"></i>
                        Washing Machine
                    </div>

                </div>


                <!-- Bedroom -->

                <div class="amenity-column">

                    <div class="amenity-column-title">
                        Bedroom
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-bed"></i>
                        Bed with Mattress
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-snowflake"></i>
                        Air Conditioning
                    </div>

                </div>


                <!-- Washroom -->

                <div class="amenity-column">

                    <div class="amenity-column-title">
                        Washroom
                    </div>

                    <div class="amenity-item">
                        <i class="fas fa-shower"></i>
                        Geyser
                    </div>

                </div>
            </div>

        </div>
        

        <!-- =====================================
             ABOUT THE PROPERTY
        ====================================== -->

        <h2 class="section-title">
            About the Property
        </h2>

        <div class="details-container">

            <div class="details-box">

                <p>
                    <?php echo nl2br($property["description"]); ?>
                </p>

            </div>

        </div>

        <!-- =====================================
            RATINGS & REVIEWS
        ====================================== -->

        <div class="ratings-section">

            <h2 class="section-title">
                Ratings & Reviews
            </h2>

            <div class="ratings-content">

                <!-- Rating Breakdown -->

                <div class="rating-breakdown">

                    <div class="rating-row">

                        <div class="rating-label">
                            Cleanliness
                        </div>

                        <div class="rating-stars">

                            <?php

                            $cleanliness = $property["cleanliness"];

                            for ($i = 1; $i <= 5; $i++) {

                                if ($i <= floor($cleanliness)) {
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }

                            }

                            ?>

                        </div>

                    </div>


                    <div class="rating-row">

                        <div class="rating-label">
                            Food Quality
                        </div>

                        <div class="rating-stars">

                            <?php

                            $food_quality = $property["food_quality"];

                            for ($i = 1; $i <= 5; $i++) {

                                if ($i <= floor($food_quality)) {
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }

                            }

                            ?>

                        </div>

                    </div>


                    <div class="rating-row">

                        <div class="rating-label">
                            Safety
                        </div>

                        <div class="rating-stars">

                            <?php

                            $safety = $property["safety"];

                            for ($i = 1; $i <= 5; $i++) {

                                if ($i <= floor($safety)) {
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }

                            }

                            ?>

                        </div>

                    </div>

                </div>


                <!-- Overall Rating -->

                <div class="overall-rating">

                    <div class="overall-rating-number">
                        <?php echo $property["rating"]; ?>
                    </div>

                    <div class="overall-rating-stars">

                        <?php

                        $overall = $property["rating"];

                        for ($i = 1; $i <= 5; $i++) {

                            if ($i <= floor($overall)) {
                                echo '<i class="fas fa-star"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }

                        }

                        ?>

                    </div>

                    <div class="overall-rating-text">
                        Overall Rating
                    </div>

                </div>

            </div>

        </div>

        <!-- =====================================
            WHAT PEOPLE SAY
        ====================================== -->

        <div class="testimonials-section">

            <h2 class="section-title">
                What People Say
            </h2>

            <div class="testimonials-container">


                <!-- Review 1 -->

                <div class="testimonial-card">

                    <div class="testimonial-avatar">
                        A
                    </div>

                    <div class="testimonial-quote">
                        “
                    </div>

                    <div class="testimonial-text">
                        The room was clean and comfortable. The location
                        was convenient and the overall stay was pleasant.
                    </div>

                    <div class="testimonial-name">
                        Ananya
                    </div>

                </div>


                <!-- Review 2 -->

                <div class="testimonial-card">

                    <div class="testimonial-avatar">
                        R
                    </div>

                    <div class="testimonial-quote">
                        “
                    </div>

                    <div class="testimonial-text">
                        I liked the peaceful environment and the facilities
                        provided. It was a comfortable place to stay.
                    </div>

                    <div class="testimonial-name">
                        Rahul
                    </div>

                </div>


                <!-- Review 3 -->

                <div class="testimonial-card">

                    <div class="testimonial-avatar">
                        P
                    </div>

                    <div class="testimonial-quote">
                        “
                    </div>

                    <div class="testimonial-text">
                        The property was well maintained and the basic
                        amenities made daily living convenient.
                    </div>

                    <div class="testimonial-name">
                        Priya
                    </div>

                </div>


            </div>

        </div>

    </div>


</div>



<?php include "includes/footer.php"; ?>


<!-- Bootstrap JavaScript -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>

function toggleInterested(propertyId, heart) {

    $.ajax({

        url: "api/toggle_interested.php",

        type: "POST",

        data: {
            property_id: propertyId
        },

        success: function(response) {

            response = response.trim();


            /* User is not logged in */

            if (response == "login") {

                alert("Please login to mark a property as interested.");

                return;

            }


            /* Property added */

            if (response == "added") {

                heart.classList.remove("far");

                heart.classList.add("fas");

                heart.style.color = "#e74c3c";

            }


            /* Property removed */

            else if (response == "removed") {

                heart.classList.remove("fas");

                heart.classList.add("far");

                heart.style.color = "#999999";

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