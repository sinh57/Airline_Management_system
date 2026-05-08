<?php 
require_once "dbconnection.php";

if(isset($_POST['submit'])){
    $catch = strtoupper(trim($_POST['flightcode']));
    
    // Validate flight code
    if(empty($catch) || strlen($catch) != 10){
        echo "<script>alert('Invalid Flight Code')</script>";
        echo "<script>window.location='deletedetails.html'</script>";
        exit();
    }
    
    // Check if flight exists using prepared statement
    $stmt = mysqli_prepare($con, "SELECT FLIGHT_CODE FROM flight WHERE FLIGHT_CODE = ?");
    mysqli_stmt_bind_param($stmt, "s", $catch);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0){
        echo "<script>alert('Flight code not in database')</script>";
        echo "<script>window.location='deletedetails.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Start transaction
    mysqli_begin_transaction($con);
    
    try {
        // Delete tickets for this flight (cascade will handle this automatically with foreign keys)
        // But we'll do it explicitly for clarity
        $stmt = mysqli_prepare($con, "DELETE FROM ticket WHERE FLIGHT_CODE = ?");
        mysqli_stmt_bind_param($stmt, "s", $catch);
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to delete tickets");
        }
        mysqli_stmt_close($stmt);
        
        // Delete flight (this is the main delete)
        $stmt = mysqli_prepare($con, "DELETE FROM flight WHERE FLIGHT_CODE = ?");
        mysqli_stmt_bind_param($stmt, "s", $catch);
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to delete flight");
        }
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        
        // Commit transaction
        mysqli_commit($con);
        
        if($affected_rows > 0){
            echo "<script>alert('Flight deleted successfully')</script>";
        } else {
            echo "<script>alert('Flight not found or already deleted')</script>";
        }
        echo "<script>window.location='homepage.html'</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Flight deletion failed: " . addslashes($e->getMessage()) . "')</script>";
        echo "<script>window.location='deletedetails.html'</script>";
    }
}

?>