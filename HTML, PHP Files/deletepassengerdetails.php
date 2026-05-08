<?php
require_once "dbconnection.php";

if(isset($_POST['submit'])){
    // Get and sanitize input
    $passportno = strtoupper(trim($_POST['passportno']));
    $ticketno = intval($_POST['ticketnumber']);
    
    // Input validation
    if(empty($passportno) || strlen($passportno) != 8){
        echo "<script>alert('Please enter a valid 8-character passport number')</script>";
        echo "<script>window.location='deletepassengerdetails.html'</script>";
        exit();
    }
    
    if($ticketno <= 0){
        echo "<script>alert('Please enter a valid ticket number')</script>";
        echo "<script>window.location='deletepassengerdetails.html'</script>";
        exit();
    }
    
    // Check if passenger exists
    $stmt = mysqli_prepare($con, "SELECT PASSPORT_NO FROM passenger WHERE PASSPORT_NO = ?");
    mysqli_stmt_bind_param($stmt, "s", $passportno);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0){
        echo "<script>alert('Passenger not found')</script>";
        echo "<script>window.location='deletepassengerdetails.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Check if ticket exists and belongs to this passenger
    $stmt = mysqli_prepare($con, "SELECT TICKET_NO, PASSPORT_NO FROM ticket WHERE TICKET_NO = ? AND PASSPORT_NO = ?");
    mysqli_stmt_bind_param($stmt, "is", $ticketno, $passportno);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0){
        echo "<script>alert('No such ticket found for this passenger')</script>";
        echo "<script>window.location='deletepassengerdetails.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Start transaction
    mysqli_begin_transaction($con);
    
    try {
        // Delete ticket (this will cascade delete passenger if no other tickets exist)
        $stmt = mysqli_prepare($con, "DELETE FROM ticket WHERE TICKET_NO = ? AND PASSPORT_NO = ?");
        mysqli_stmt_bind_param($stmt, "is", $ticketno, $passportno);
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to delete ticket");
        }
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        
        // Check if passenger has any other tickets
        $stmt = mysqli_prepare($con, "SELECT COUNT(*) as count FROM ticket WHERE PASSPORT_NO = ?");
        mysqli_stmt_bind_param($stmt, "s", $passportno);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $ticket_count = $row['count'];
        mysqli_stmt_close($stmt);
        
        // If no more tickets, delete passenger
        if($ticket_count == 0){
            $stmt = mysqli_prepare($con, "DELETE FROM passenger WHERE PASSPORT_NO = ?");
            mysqli_stmt_bind_param($stmt, "s", $passportno);
            if(!mysqli_stmt_execute($stmt)){
                throw new Exception("Failed to delete passenger");
            }
            mysqli_stmt_close($stmt);
        }
        
        // Commit transaction
        mysqli_commit($con);
        
        echo "<script>alert('Ticket deleted successfully')</script>";
        echo "<script>window.location='passengerchoice.html'</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Ticket deletion failed: " . addslashes($e->getMessage()) . "')</script>";
        echo "<script>window.location='deletepassengerdetails.html'</script>";
    }
}

?>