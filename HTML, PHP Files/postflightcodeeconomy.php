<?php 
require_once "dbconnection.php";
session_start();

if(isset($_GET['id'])){
    $id = trim($_GET['id']);
    
    // Validate flight code
    if(empty($id) || strlen($id) != 10){
        echo "<script>alert('Invalid Flight Code')</script>";
        echo "<script>window.location='homepage.html'</script>";
        exit();
    }
    
    // Check if flight exists and get economy class price
    $stmt = mysqli_prepare($con, "SELECT FLIGHT_CODE, PRICE_ECONOMY FROM flight WHERE FLIGHT_CODE = ?");
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0){
        echo "<script>alert('Flight not found')</script>";
        echo "<script>window.location='homepage.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    
    $row = mysqli_fetch_assoc($result);
    $price = $row['PRICE_ECONOMY'];
    mysqli_stmt_close($stmt);
    
    // Store flight selection in session
    $_SESSION['selected_flight'] = $id;
    $_SESSION['ticket_type'] = 'ECONOMY CLASS';
    $_SESSION['ticket_price'] = $price;
    
    echo "<script>window.location='Passenger_Details.html'</script>";
}
else{
    echo "<script>alert('Please select a flight first')</script>";
    echo "<script>window.location='homepage.html'</script>";
}
 ?>