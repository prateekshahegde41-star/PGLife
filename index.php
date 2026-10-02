<?php

session_start();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Welcome | PG Life</title>

    <?php
    include "includes/head_links.php";
    ?>

</head>

<body>

    <?php

    include "includes/header.php";
    include "includes/signup_modal.php";
    include "includes/login_modal.php";

    ?>


    <div class="banner">

        <div class="container">

            <h1>
                Happiness per Square Foot
            </h1>


            <form action="property_list.php" method="get" class="search-form">

                <div class="input-group">

                    <input type="text"
                        name="city"
                        class="form-control"
                        placeholder="Enter your city to search for PGs"
                        required>

                    <div class="input-group-append">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search"></i>
                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="major-cities">

        <div class="container">

            <h2>Major Cities</h2>


            <div class="row">

                <!-- Delhi -->
                <div class="col-6 col-md-3">

                    <a href="property_list.php?city=Delhi">

                        <div class="city-card">

                            <img src="img/delhi.png" alt="Delhi">

                            <p>DELHI</p>

                        </div>

                    </a>

                </div>


                <!-- Mumbai -->
                <div class="col-6 col-md-3">

                    <a href="property_list.php?city=Mumbai">

                        <div class="city-card">

                            <img src="img/mumbai.png" alt="Mumbai">

                            <p>MUMBAI</p>

                        </div>

                    </a>

                </div>


                <!-- Bengaluru -->
                <div class="col-6 col-md-3">

                    <a href="property_list.php?city=Bangalore">

                        <div class="city-card">

                            <img src="img/bangalore.png" alt="Bengaluru">

                            <p>BENGALURU</p>

                        </div>

                    </a>

                </div>


                <!-- Hyderabad -->
                <div class="col-6 col-md-3">

                    <a href="property_list.php?city=Hyderabad">

                        <div class="city-card">

                            <img src="img/hyderabad.png" alt="Hyderabad">

                            <p>HYDERABAD</p>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>


    <?php

    include "includes/footer.php";

    ?>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>

</html>