<?php
require_once "dbconnection.php";
session_start();

// Get flight code from session (set by modifyadmindetailsview.php)
$flight = isset($_SESSION['selected_flight']) ? $_SESSION['selected_flight'] : '';

if(empty($flight)){
    echo "<script>alert('No flight selected')</script>";
    echo "<script>window.location='modifyadmindetails.html'</script>";
    exit();
}

if(isset($_POST['submit'])){
    $count = 0;
    $flag = 0;
    $updates = array();
    $params = array();
    $types = "";
    
    // Build update query dynamically based on provided fields
    if(isset($_POST['departure']) && !empty($_POST['departure'])){
        $departure = trim($_POST['departure']);
        $updates[] = "DEPARTURE = ?";
        $params[] = $departure;
        $types .= "s";
    }
    
    if(isset($_POST['arrival']) && !empty($_POST['arrival'])){
        $arrival = trim($_POST['arrival']);
        $updates[] = "ARRIVAL = ?";
        $params[] = $arrival;
        $types .= "s";
    }
    
    if(isset($_POST['duration']) && !empty($_POST['duration'])){
        $duration = trim($_POST['duration']);
        $updates[] = "DURATION = ?";
        $params[] = $duration;
        $types .= "s";
    }
    
    if(isset($_POST['businessclass']) && !empty($_POST['businessclass'])){
        $businessclass = floatval($_POST['businessclass']);
        if($businessclass < 0){
            echo "<script>alert('Price cannot be negative')</script>";
            echo "<script>window.location='modifyadmindetails.html'</script>";
            exit();
        }
        $updates[] = "PRICE_BUSINESS = ?";
        $params[] = $businessclass;
        $types .= "d";
    }
    
    if(isset($_POST['economyclass']) && !empty($_POST['economyclass'])){
        $economyclass = floatval($_POST['economyclass']);
        if($economyclass < 0){
            echo "<script>alert('Price cannot be negative')</script>";
            echo "<script>window.location='modifyadmindetails.html'</script>";
            exit();
        }
        $updates[] = "PRICE_ECONOMY = ?";
        $params[] = $economyclass;
        $types .= "d";
    }
    
    if(isset($_POST['students']) && !empty($_POST['students'])){
        $students = floatval($_POST['students']);
        if($students < 0){
            echo "<script>alert('Price cannot be negative')</script>";
            echo "<script>window.location='modifyadmindetails.html'</script>";
            exit();
        }
        $updates[] = "PRICE_STUDENTS = ?";
        $params[] = $students;
        $types .= "d";
    }
    
    if(isset($_POST['diff']) && !empty($_POST['diff'])){
        $diff = floatval($_POST['diff']);
        if($diff < 0){
            echo "<script>alert('Price cannot be negative')</script>";
            echo "<script>window.location='modifyadmindetails.html'</script>";
            exit();
        }
        $updates[] = "PRICE_DIFFERENTLYABLED = ?";
        $params[] = $diff;
        $types .= "d";
    }
    
    // Check if any updates were requested
    if(empty($updates)){
        echo "<script>alert('No fields to update')</script>";
        echo "<script>window.location='modifyadmindetails.html'</script>";
        exit();
    }
    
    // Add flight code to parameters
    $params[] = $flight;
    $types .= "s";
    
    // Start transaction
    mysqli_begin_transaction($con);
    
    try {
        // Build and execute update query
        $sql = "UPDATE flight SET " . implode(", ", $updates) . " WHERE FLIGHT_CODE = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to update flight details");
        }
        
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        
        // Commit transaction
        mysqli_commit($con);
        
        // Clear session
        unset($_SESSION['selected_flight']);
        
        if($affected_rows > 0){
            echo "<script>alert('Data Modified Successfully')</script>";
        } else {
            echo "<script>alert('No changes made (flight not found or no changes needed)')</script>";
        }
        echo "<script>window.location='homepage.html'</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Data Modification Failed: " . addslashes($e->getMessage()) . "')</script>";
        echo "<script>window.location='modifyadmindetails.html'</script>";
    }
}
?>