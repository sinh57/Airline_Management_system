<?php 
// Database connection with proper error handling and charset
$con = mysqli_connect('localhost', 'root', '', 'dbms');

if (mysqli_connect_errno($con)) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for proper character encoding
if (!mysqli_set_charset($con, "utf8mb4")) {
    die("Error setting character set: " . mysqli_error($con));
}

// Enable error reporting for development (disable in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
 ?>
