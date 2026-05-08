<?php
require_once "dbconnection.php";
session_start();

// Get passport number from session (set by modifypassengerdetailsview.php)
$passportno = isset($_SESSION['selected_passport']) ? $_SESSION['selected_passport'] : '';

if(empty($passportno)){
    echo "<script>alert('No passenger selected')</script>";
    echo "<script>window.location='modifypassengerdetails.html'</script>";
    exit();
}

if(isset($_POST['submit'])){
    $count = 0;
    $updates = array();
    $params = array();
    $types = "";
    
    // Build update query dynamically based on provided fields
    if(isset($_POST['firstname']) && !empty($_POST['firstname'])){
        $fname = trim($_POST['firstname']);
        $updates[] = "FNAME = ?";
        $params[] = $fname;
        $types .= "s";
    }
    
    if(isset($_POST['middlename']) && !empty($_POST['middlename'])){
        $mname = trim($_POST['middlename']);
        $updates[] = "MNAME = ?";
        $params[] = $mname;
        $types .= "s";
    }
    
    if(isset($_POST['lastname']) && !empty($_POST['lastname'])){
        $lname = trim($_POST['lastname']);
        $updates[] = "LNAME = ?";
        $params[] = $lname;
        $types .= "s";
    }
    
    if(isset($_POST['age']) && !empty($_POST['age'])){
        $age = intval($_POST['age']);
        if($age < 0 || $age > 120){
            echo "<script>alert('Please enter a valid age')</script>";
            echo "<script>window.location='modifypassengerdetails.html'</script>";
            exit();
        }
        $updates[] = "AGE = ?";
        $params[] = $age;
        $types .= "i";
    }
    
    if(isset($_POST['sex']) && !empty($_POST['sex'])){
        $sex = strtoupper(trim($_POST['sex']));
        if(!in_array($sex, ['M', 'F', 'O'])){
            echo "<script>alert('Please enter valid sex (M/F/O)')</script>";
            echo "<script>window.location='modifypassengerdetails.html'</script>";
            exit();
        }
        $updates[] = "SEX = ?";
        $params[] = $sex;
        $types .= "s";
    }
    
    if(isset($_POST['phonenumber']) && !empty($_POST['phonenumber'])){
        $phonenumber = trim($_POST['phonenumber']);
        if(!preg_match('/^[0-9]{10}$/', $phonenumber)){
            echo "<script>alert('Phone Number should be 10 digits')</script>";
            echo "<script>window.location='modifypassengerdetails.html'</script>";
            exit();
        }
        $updates[] = "PHONE = ?";
        $params[] = $phonenumber;
        $types .= "s";
    }
    
    if(isset($_POST['address']) && !empty($_POST['address'])){
        $address = trim($_POST['address']);
        $updates[] = "ADDRESS = ?";
        $params[] = $address;
        $types .= "s";
    }
    
    // Check if any updates were requested
    if(empty($updates)){
        echo "<script>alert('No fields to update')</script>";
        echo "<script>window.location='modifypassengerdetails.html'</script>";
        exit();
    }
    
    // Add passport number to parameters
    $params[] = $passportno;
    $types .= "s";
    
    // Start transaction
    mysqli_begin_transaction($con);
    
    try {
        // Build and execute update query
        $sql = "UPDATE passenger SET " . implode(", ", $updates) . " WHERE PASSPORT_NO = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to update passenger details");
        }
        
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        
        // Commit transaction
        mysqli_commit($con);
        
        // Clear session
        unset($_SESSION['selected_passport']);
        
        if($affected_rows > 0){
            echo "<script>alert('Data Modified Successfully')</script>";
            echo "<script>window.location='passengerchoice.html'</script>";
        } else {
            echo "<script>alert('No changes made (passenger not found or no changes needed)')</script>";
            echo "<script>window.location='modifypassengerdetails.html'</script>";
        }
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Data Modification Failed: " . addslashes($e->getMessage()) . "')</script>";
        echo "<script>window.location='modifypassengerdetails.html'</script>";
    }
}
?>

