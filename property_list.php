<?php

session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include "includes/database_connect.php";

$city = $_GET["city"];

$filter = isset($_GET["filter"]) ? $_GET["filter"] : "No Filter";
$sort = isset($_GET["sort"]) ? $_GET["sort"] : "";


$sql = "SELECT * FROM properties WHERE city = '$city'";


/* Gender Filter */

if ($filter == "Male") {

    $sql .= " AND gender = 'Male'";

}
else if ($filter == "Female") {

    $sql .= " AND gender = 'Female'";

}
else if ($filter == "Unisex") {

    $sql .= " AND gender = 'Unisex'";

}


/* Rent Sorting */

if ($sort == "high") {

    $sql .= " ORDER BY price DESC";

}
else if ($sort == "low") {

    $sql .= " ORDER BY price ASC";

}


$result = mysqli_query($con, $sql);

$interested_properties = [];

if (isset($_SESSION["user_id"])) {

    $user_id = $_SESSION["user_id"];

    $interest_sql = "
        SELECT property_id
        FROM interested_users
        WHERE user_id = '$user_id'
    ";

    $interest_result = mysqli_query($con, $interest_sql);

    while ($interest = mysqli_fetch_assoc($interest_result)) {

        $interested_properties[] = $interest["property_id"];

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>PGs in <?php echo $city; ?> | PG Life</title>

    <?php include "includes/head_links.php"; ?>

    <style>

        body {
            background-color: #f5f5f5;
        }

        .property-list-container {
            padding-top: 25px;
            padding-bottom: 50px;
        }

        /* Breadcrumb */

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 25px;
        }

        .breadcrumb a {
            color: #51c6c6;
            text-decoration: none;
        }


        /* Property Card */

        .property-card {
            background-color: white;
            border: 1px solid #dddddd;
            border-radius: 4px;
            margin-bottom: 25px;
            padding: 15px;
        }


        /* Property Image */

        .property-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }


        /* Property Details */

        .property-name {
            font-size: 20px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
        }

        .property-location {
            color: #777777;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .property-location i {
            margin-right: 5px;
        }


        /* Price */

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


        /* Gender */

        .property-gender {
            font-size: 14px;
            color: #777777;
            margin-top: 10px;
        }


        /* Rating */

        .property-rating {
            margin-top: 12px;
            color: #f5a623;
        }


        /* Interested */

        .interested-section {
            text-align: center;
            margin-top: 0;
            padding-top: 5px;
        }

        .interested-icon {
            font-size: 25px;
            color: #999999;
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


        /* Details Button */

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


        /* Heading */

        .page-title {
            font-size: 26px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #777777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .property-action-column {
            min-width: 0;
            overflow: hidden;
        }

        /* Filter and Sort */

        .property-filters {
            display: flex;
            align-items: center;
            gap: 35px;
            margin-bottom: 25px;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #555;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .filter-item:hover {
            color: #222;
            text-decoration: none;
        }

        .filter-item i {
            font-size: 14px;
        }


        /* Filter Popup */

        .filter-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 9998;
        }

        .filter-overlay.show {
            display: block;
        }

        .filter-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 520px;
            max-width: 90%;
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.25);
            z-index: 9999;
        }

        .filter-popup.show {
            display: block;
        }

        .filter-popup-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .filter-options {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-option {
            border: 1px solid #ccc;
            background: white;
            color: #444;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .filter-option:hover {
            border-color: #222;
        }

        .filter-option.active {
            background: #000;
            color: white;
            border-color: #000;
        }

        .filter-ok {
            margin-top: 22px;
            text-align: right;
        }

        .filter-ok button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 25px;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-ok button:hover {
            background-color: #218838;
        }


        /* Responsive */

        @media (max-width: 575px) {

            .property-filters {
                gap: 20px;
                flex-wrap: wrap;
            }

            .filter-popup {
                width: 90%;
            }

        }

    </style>

    <!-- React -->
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>

</head>


<body>


<?php

include "includes/header.php";

include "includes/signup_modal.php";

include "includes/login_modal.php";

?>


<div class="container property-list-container">


    <!-- Breadcrumb -->

    <div class="breadcrumb">

        <a href="index.php">
            Home
        </a>

        <span class="mx-2">
            /
        </span>

        <span>
            <?php echo $city; ?>
        </span>

    </div>


    <!-- Page Heading -->

    <div class="page-title">

        PGs in <?php echo $city; ?>

    </div>

    <div class="page-subtitle">

        Find the perfect PG accommodation in <?php echo $city; ?>

    </div>

    <!-- Filter and Sort -->

    <div class="property-filters">

        <!-- Filters -->

        <div
            class="filter-item"
            onclick="openFilterPopup()"
        >

            <i class="fas fa-filter"></i>

            <span>Filters</span>

        </div>


        <!-- Highest Rent -->

        <a
            href="property_list.php?city=<?php echo urlencode($city); ?>&sort=high"
            class="filter-item"
        >

            <i class="fas fa-sort-amount-down"></i>

            <span>Highest Rent First</span>

            <span class="sort-arrow"></span>

        </a>


        <!-- Lowest Rent -->

        <a
            href="property_list.php?city=<?php echo urlencode($city); ?>&sort=low"
            class="filter-item"
        >

            <i class="fas fa-sort-amount-up"></i>

            <span>Lowest Rent First</span>

            

        </a>

    </div>


    <!-- Property List -->

    <div id="react-property-list"></div>

    <?php

    /* Prepare PHP data for the React component */

    $react_properties = [];

    while ($property = mysqli_fetch_assoc($result)) {

        $property_id = (int) $property["id"];

        $image_folder = "img/properties/" . $property_id . "/";

        $images = glob(
            $image_folder . "*.{jpg,jpeg,png}",
            GLOB_BRACE
        );

        $react_properties[] = [
            "id" => $property_id,
            "name" => $property["name"],
            "city" => $property["city"],
            "price" => $property["price"],
            "gender" => $property["gender"],
            "rating" => (float) $property["rating"],
            "image" => count($images) > 0 ? $images[0] : "",
            "interested" => in_array($property_id, $interested_properties)
        ];

    }

    ?>


</div>

<!-- Filter Overlay -->

<div
    class="filter-overlay"
    id="filterOverlay"
    onclick="closeFilterPopup()"
></div>


<!-- Filter Popup -->

<div
    class="filter-popup"
    id="filterPopup"
>

    <div class="filter-popup-title">
        Filters
    </div>


    <div class="filter-options">

        <button
            type="button"
            class="filter-option active"
            data-filter="No Filter"
            onclick="selectFilter(this)"
        >
            No Filter
        </button>


        <button
            type="button"
            class="filter-option"
            data-filter="Unisex"
            onclick="selectFilter(this)"
        >
            Unisex
        </button>


        <button
            type="button"
            class="filter-option"
            data-filter="Male"
            onclick="selectFilter(this)"
        >
            Male
        </button>


        <button
            type="button"
            class="filter-option"
            data-filter="Female"
            onclick="selectFilter(this)"
        >
            Female
        </button>

    </div>


    <div class="filter-ok">

        <button
            type="button"
            onclick="applyFilter()"
        >
            OK
        </button>

    </div>

</div>

<?php include "includes/footer.php"; ?>


<script>

    /* =============================
       React Property List Component
       ============================= */

    const propertyData = <?php echo json_encode($react_properties); ?>;

    function PropertyCard({ property }) {

        const heartClass = property.interested ? "fas" : "far";
        const heartColor = property.interested ? "#e74c3c" : "#999999";

        return React.createElement(
            "div",
            { className: "property-card", key: property.id },
            React.createElement(
                "div",
                { className: "row align-items-center" },

                React.createElement(
                    "div",
                    { className: "col-md-4" },
                    property.image
                        ? React.createElement("img", {
                              src: property.image,
                              className: "property-image",
                              alt: property.name
                          })
                        : null
                ),

                React.createElement(
                    "div",
                    { className: "col-md-5" },

                    React.createElement(
                        "div",
                        { className: "property-name" },
                        property.name
                    ),

                    React.createElement(
                        "div",
                        { className: "property-location" },
                        React.createElement("i", { className: "fas fa-map-marker-alt" }),
                        " ",
                        property.city
                    ),

                    React.createElement(
                        "div",
                        { className: "property-price" },
                        "₹" + property.price,
                        React.createElement("span", null, "/month")
                    ),

                    React.createElement(
                        "div",
                        { className: "property-gender" },
                        React.createElement("i", { className: "fas fa-user" }),
                        " ",
                        property.gender.charAt(0).toUpperCase() + property.gender.slice(1)
                    ),

                    React.createElement(
                        "div",
                        { className: "property-rating" },
                        [1, 2, 3, 4, 5].map(function(star) {
                            return React.createElement("i", {
                                key: star,
                                className: star <= property.rating ? "fas fa-star" : "far fa-star"
                            });
                        }),
                        React.createElement(
                            "span",
                            { className: "ml-2" },
                            property.rating + "/5"
                        )
                    )
                ),

                React.createElement(
                    "div",
                    { className: "col-md-3 text-center property-action-column" },

                    React.createElement(
                        "div",
                        { className: "interested-section" },
                        React.createElement(
                            "i",
                            {
                                className: heartClass + " fa-heart interested-icon",
                                style: { color: heartColor },
                                onClick: function(event) {
                                    window.toggleInterested(property.id, event.currentTarget);
                                }
                            }
                        ),
                        React.createElement(
                            "span",
                            { className: "interested-text" },
                            "Interested"
                        )
                    ),

                    React.createElement(
                        "div",
                        { className: "mt-4" },
                        React.createElement(
                            "a",
                            {
                                href: "property_detail.php?id=" + property.id,
                                className: "btn details-button"
                            },
                            "View"
                        )
                    )
                )
            )
        );
    }


    function PropertyList({ properties }) {

        return React.createElement(
            "div",
            { className: "row" },
            React.createElement(
                "div",
                { className: "col-12" },
                properties.map(function(property) {
                    return React.createElement(PropertyCard, {
                        key: property.id,
                        property: property
                    });
                })
            )
        );
    }


    ReactDOM.createRoot(
        document.getElementById("react-property-list")
    ).render(
        React.createElement(PropertyList, { properties: propertyData })
    );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>

let selectedFilter = "No Filter";


function openFilterPopup() {

    document
        .getElementById("filterPopup")
        .classList.add("show");

    document
        .getElementById("filterOverlay")
        .classList.add("show");

}


function closeFilterPopup() {

    document
        .getElementById("filterPopup")
        .classList.remove("show");

    document
        .getElementById("filterOverlay")
        .classList.remove("show");

}


function selectFilter(button) {

    document
        .querySelectorAll(".filter-option")
        .forEach(function(option) {

            option.classList.remove("active");

        });

    button.classList.add("active");

    selectedFilter = button.getAttribute("data-filter");

}


function applyFilter() {

    let city = "<?php echo addslashes($city); ?>";

    let url =
        "property_list.php?city="
        + encodeURIComponent(city)
        + "&filter="
        + encodeURIComponent(selectedFilter);

    window.location.href = url;

}

</script>

<script>

window.toggleInterested = function(propertyId, heart) {

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

};

</script>

</body>

</html>