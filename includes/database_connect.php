<?php

$con = mysqli_connect("localhost", "root", "", "pglife");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>