<?php

session_start();

include "includes/database_connect.php";

/* User must be logged in */
if (!isset($_SESSION["user_id"])) {

    echo "<script>";
    echo "alert('Please login before booking a property.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";

    exit;
}


/* Get property ID */
$property_id = $_GET["id"];


/* Fetch property */
$sql = "SELECT * FROM properties WHERE id = $property_id";

$result = mysqli_query($con, $sql);


if (mysqli_num_rows($result) == 0) {

    echo "Property not found.";
    exit();

}


$property = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Book Property - PGLife</title>

    <?php include "includes/head_links.php"; ?>

    <style>

        body {
            background-color: #f7f7f7;
        }

        .booking-container {
            margin-top: 50px;
            margin-bottom: 60px;
        }

        .booking-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 30px;
            max-width: 700px;
            margin: auto;
        }

        .booking-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #333;
        }

        .property-summary {
            background-color: #f8f1e3;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 25px;
        }

        .property-summary h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 22px;
        }

        .property-summary p {
            margin-bottom: 6px;
            color: #555;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .book-submit-btn {
            background-color: #51c6c6;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .book-submit-btn:hover {
            background-color: #3fb1b1;
        }

        @media (max-width: 767px) {

            .booking-card {
                padding: 20px;
            }

            .booking-title {
                font-size: 24px;
            }

        }

    </style>

</head>


<body>

<?php include "includes/header.php"; ?>


<div class="container booking-container">

    <div class="booking-card">

        <div class="booking-title">
            Book Now
        </div>


        <!-- Property information -->

        <div class="property-summary">

            <h3>
                <?php echo $property["name"]; ?>
            </h3>

            <p>
                <strong>City:</strong>
                <?php echo $property["city"]; ?>
            </p>

            <p>
                <strong>Monthly Rent:</strong>
                ₹<?php echo $property["price"]; ?>
            </p>

            <p>
                <strong>Gender:</strong>
                <?php echo ucfirst($property["gender"]); ?>
            </p>

        </div>


        <!-- Booking form -->

        <form action="api/booking_submit.php" method="POST">

            <input
                type="hidden"
                name="property_id"
                value="<?php echo $property_id; ?>"
            >


            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="full_name"
                    class="form-control"
                    value="<?php echo $_SESSION["full_name"]; ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Phone Number
                </label>

                <input
                    type="tel"
                    name="phone"
                    class="form-control"
                    maxlength="10"
                    pattern="[0-9]{10}"
                    placeholder="Enter 10 digit phone number"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Move-in Date
                </label>

                <input
                    type="date"
                    name="move_in_date"
                    class="form-control"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Duration (in months)
                </label>

                <input
                    type="number"
                    name="duration"
                    class="form-control"
                    min="1"
                    max="24"
                    placeholder="Enter number of months"
                    required
                >

            </div>


            <button
                type="submit"
                class="book-submit-btn"
            >
                Confirm Booking
            </button>

        </form>

    </div>

</div>


<?php include "includes/footer.php"; ?>


</body>

</html>