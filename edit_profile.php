<?php

session_start();

include "includes/database_connect.php";


/* =====================================
   CHECK LOGIN
===================================== */

if (!isset($_SESSION["user_id"])) {

    echo "<script>";
    echo "alert('Please login first.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";

    exit;
}


/* =====================================
   FETCH USER
===================================== */

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id = '$user_id'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("User query failed: " . mysqli_error($con));
}

$user = mysqli_fetch_assoc($result);

?>


<!DOCTYPE html>
<html lang="en">


<head>

    <title>Edit Profile - PGLife</title>

    <?php include "includes/head_links.php"; ?>


    <style>

        body {
            background-color: #f5f5f5;
        }


        .edit-profile-container {
            padding-top: 40px;
            padding-bottom: 60px;
        }


        .edit-profile-card {
            max-width: 700px;
            margin: auto;
            background-color: white;
            border: 1px solid #dddddd;
            border-radius: 5px;
            padding: 30px;
        }


        .edit-profile-title {
            font-size: 28px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 30px;
            text-align: center;
        }


        /* =====================================
           PROFILE PHOTO
        ===================================== */

        .profile-photo-section {
            text-align: center;
            margin-bottom: 30px;
        }


        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f8f1e3;
            margin: 0 auto 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;
        }


        .profile-photo i {
            font-size: 48px;
            color: #51c6c6;
        }


        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .photo-label {
            color: #51c6c6;
            font-size: 14px;
            cursor: pointer;
        }


        .photo-label:hover {
            color: #43b5b5;
        }


        #profile_image {
            display: none;
        }


        /* =====================================
           FORM
        ===================================== */

        .form-group {
            margin-bottom: 20px;
        }


        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #444444;
            margin-bottom: 7px;
        }


        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }


        .form-control:focus {
            border-color: #51c6c6;
            box-shadow: none;
            outline: none;
        }


        /* =====================================
           BUTTONS
        ===================================== */

        .profile-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }


        .save-button {
            background-color: #51c6c6;
            border: 1px solid #51c6c6;
            color: white;
            padding: 10px 25px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }


        .save-button:hover {
            background-color: #43b5b5;
            border-color: #43b5b5;
        }


        .cancel-button {
            background-color: white;
            border: 1px solid #cccccc;
            color: #555555;
            padding: 10px 25px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
        }


        .cancel-button:hover {
            background-color: #f5f5f5;
            color: #333333;
            text-decoration: none;
        }


        /* =====================================
           MOBILE
        ===================================== */

        @media (max-width: 767px) {

            .edit-profile-card {
                padding: 20px;
            }

            .edit-profile-title {
                font-size: 24px;
            }

            .profile-buttons {
                flex-direction: column;
            }

            .save-button,
            .cancel-button {
                text-align: center;
                width: 100%;
                box-sizing: border-box;
            }

        }

    </style>

</head>


<body>


<?php include "includes/header.php"; ?>


<div class="container edit-profile-container">


    <div class="edit-profile-card">


        <div class="edit-profile-title">

            Edit Profile

        </div>


        <!-- =====================================
             EDIT FORM
        ====================================== -->

        <form
            action="api/update_profile.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- =====================================
                 PROFILE PHOTO
            ====================================== -->

            <div class="profile-photo-section">


                <div class="profile-photo" id="photoPreview">


                    <?php if (!empty($user["profile_image"])) { ?>


                        <img
                            src="<?php echo htmlspecialchars($user["profile_image"]); ?>"
                            alt="Profile Photo"
                        >


                    <?php } else { ?>


                        <i class="fas fa-user"></i>


                    <?php } ?>


                </div>


                <label
                    for="profile_image"
                    class="photo-label"
                >
                    Change Photo
                </label>


                <input
                    type="file"
                    id="profile_image"
                    name="profile_image"
                    accept=".jpg,.jpeg,.png"
                >


            </div>


            <!-- =====================================
                 FULL NAME
            ====================================== -->

            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="full_name"
                    class="form-control"
                    value="<?php echo htmlspecialchars($user["full_name"]); ?>"
                    required
                >

            </div>


            <!-- =====================================
                 PHONE
            ====================================== -->

            <div class="form-group">

                <label>
                    Phone Number
                </label>

                <input
                    type="tel"
                    name="phone"
                    class="form-control"
                    value="<?php echo htmlspecialchars($user["phone"]); ?>"
                    pattern="[0-9]{10}"
                    maxlength="10"
                    required
                >

            </div>


            <!-- =====================================
                 EMAIL
            ====================================== -->

            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo htmlspecialchars($user["email"]); ?>"
                    required
                >

            </div>


            <!-- =====================================
                 COLLEGE
            ====================================== -->

            <div class="form-group">

                <label>
                    College Name
                </label>

                <input
                    type="text"
                    name="college_name"
                    class="form-control"
                    value="<?php echo htmlspecialchars($user["college_name"]); ?>"
                    required
                >

            </div>


            <!-- =====================================
                 GENDER
            ====================================== -->

            <div class="form-group">

                <label>
                    Gender
                </label>

                <select
                    name="gender"
                    class="form-control"
                    required
                >

                    <option
                        value="male"
                        <?php echo ($user["gender"] == "male") ? "selected" : ""; ?>
                    >
                        Male
                    </option>


                    <option
                        value="female"
                        <?php echo ($user["gender"] == "female") ? "selected" : ""; ?>
                    >
                        Female
                    </option>

                </select>

            </div>


            <!-- =====================================
                 ADDRESS
            ====================================== -->

            <div class="form-group">

                <label>
                    Address
                </label>

                <textarea
                    name="address"
                    class="form-control"
                    rows="3"
                    placeholder="Enter your address"
                    required
                ><?php echo htmlspecialchars($user["address"] ?? ""); ?></textarea>

            </div>


            <!-- =====================================
                 BUTTONS
            ====================================== -->

            <div class="profile-buttons">


                <button
                    type="submit"
                    class="save-button"
                >
                    Save Changes
                </button>


                <a
                    href="dashboard.php"
                    class="cancel-button"
                >
                    Cancel
                </a>


            </div>


        </form>


    </div>

</div>


<?php include "includes/footer.php"; ?>


<!-- =====================================
     PHOTO PREVIEW
===================================== -->

<script>

document.getElementById("profile_image").addEventListener("change", function(event) {

    let file = event.target.files[0];

    if (file) {

        let reader = new FileReader();

        reader.onload = function(e) {

            document.getElementById("photoPreview").innerHTML =
                '<img src="' + e.target.result + '" alt="Profile Photo">';

        };

        reader.readAsDataURL(file);

    }

});

</script>


</body>

</html>