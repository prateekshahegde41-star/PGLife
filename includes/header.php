<nav class="navbar navbar-expand-md navbar-light bg-white sticky-top">

    <div class="container">

        <!-- PGLife Logo -->
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.png" alt="PGLife" height="40">
        </a>

        <!-- Mobile Menu Button -->
        <button class="navbar-toggler" type="button"
                data-toggle="collapse"
                data-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ml-auto">

                <?php

                if (!isset($_SESSION["user_id"])) {

                ?>

                    <!-- Signup -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"
                           data-toggle="modal"
                           data-target="#signup-modal">

                            <i class="fas fa-user-plus"></i>
                            Signup

                        </a>
                    </li>


                    <!-- Login -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"
                           data-toggle="modal"
                           data-target="#login-modal">

                            <i class="fas fa-sign-in-alt"></i>
                            Login

                        </a>
                    </li>

                <?php

                } else {

                ?>

                    <!-- User Name -->
                    <li class="nav-item">
                        <span class="nav-link">

                            Hi,
                            <?php echo $_SESSION["full_name"]; ?>

                        </span>
                    </li>


                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">

                            <i class="fas fa-user"></i>
                            Dashboard

                        </a>
                    </li>


                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">

                            <i class="fas fa-sign-out-alt"></i>
                            Logout

                        </a>
                    </li>

                <?php

                }

                ?>

            </ul>

        </div>

    </div>

</nav>