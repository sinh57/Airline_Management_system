<?php 
require_once "dbconnection.php";

if(isset($_POST['submit'])){
    // Get and sanitize input
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $age = intval($_POST['age']);
    $sex = strtoupper(trim($_POST['Sex']));
    $phonenumber = trim($_POST['phonenumber']);
    $address = trim($_POST['address']);
    $passportnumber = strtoupper(trim($_POST['passportnumber']));
    $flight_code = isset($_SESSION['selected_flight']) ? $_SESSION['selected_flight'] : '';
    $ticket_type = isset($_SESSION['ticket_type']) ? $_SESSION['ticket_type'] : '';
    $ticket_price = isset($_SESSION['ticket_price']) ? $_SESSION['ticket_price'] : 0;

    // Input validation
    if(empty($firstname) || empty($lastname) || empty($passportnumber) || empty($age) || empty($sex) || empty($phonenumber) || empty($address)){
        echo "<script>alert('⚠ Please fill all required fields')</script>";
        echo "<script>window.location='Passenger_Details.html'</script>";
        exit();
    }

    // Validate passport number (8 characters)
    if(strlen($passportnumber) != 8){
        echo "<script>alert('⚠ Passport Number must be exactly 8 characters')</script>";
        echo "<script>window.location='Passenger_Details.html'</script>";
        exit();
    }

    // Validate phone number (10 digits)
    if(!preg_match('/^[0-9]{10}$/', $phonenumber)){
        echo "<script>alert('⚠ Phone Number must be exactly 10 digits')</script>";
        echo "<script>window.location='Passenger_Details.html'</script>";
        exit();
    }

    // Validate age
    if($age < 0 || $age > 120){
        echo "<script>alert('⚠ Please enter a valid age (1-120)')</script>";
        echo "<script>window.location='Passenger_Details.html'</script>";
        exit();
    }

    // Validate sex
    if(!in_array($sex, ['M', 'F', 'O'])){
        echo "<script>alert('⚠ Please enter valid sex (M/F/O)')</script>";
        echo "<script>window.location='Passenger_Details.html'</script>";
        exit();
    }

    // Check if flight was selected
    if(empty($flight_code)){
        echo "<script>alert('⚠ No flight selected. Please search and select a flight first.')</script>";
        echo "<script>window.location='searchflight.html'</script>";
        exit();
    }

    // Check if passport already exists
    $stmt = mysqli_prepare($con, "SELECT PASSPORT_NO FROM passenger WHERE PASSPORT_NO = ?");
    mysqli_stmt_bind_param($stmt, "s", $passportnumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('⚠ This passport number already exists. Please use a unique passport number.')</script>";
        echo "<script>window.location='searchflight.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);

    // Get flight details
    $stmt = mysqli_prepare($con, "SELECT SOURCE, DESTINATION, DATE FROM flight WHERE FLIGHT_CODE = ?");
    mysqli_stmt_bind_param($stmt, "s", $flight_code);
    mysqli_stmt_execute($stmt);
    $flight_result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($flight_result) == 0){
        echo "<script>alert('⚠ Flight not found. Please select a valid flight.')</script>";
        echo "<script>window.location='searchflight.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    
    $flight_data = mysqli_fetch_assoc($flight_result);
    $source = $flight_data['SOURCE'];
    $destination = $flight_data['DESTINATION'];
    $date = $flight_data['DATE'];
    mysqli_stmt_close($stmt);

    // Start transaction
    mysqli_begin_transaction($con);

    try {
        // Insert passenger
        $stmt = mysqli_prepare($con, "INSERT INTO passenger (FNAME, MNAME, LNAME, PASSPORT_NO, AGE, SEX, PHONE, ADDRESS) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssisss", $firstname, $middlename, $lastname, $passportnumber, $age, $sex, $phonenumber, $address);
        
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to insert passenger details");
        }
        mysqli_stmt_close($stmt);

        // Insert ticket
        $stmt = mysqli_prepare($con, "INSERT INTO ticket (PRICE, SOURCE, DESTINATION, DATE_OF_TRAVEL, PASSPORT_NO, FLIGHT_CODE, TYPE) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "dssssss", $ticket_price, $source, $destination, $date, $passportnumber, $flight_code, $ticket_type);
        
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to book ticket");
        }
        mysqli_stmt_close($stmt);

        // Commit transaction
        mysqli_commit($con);

        // Store passport in session for ticket review
        $_SESSION['last_passport'] = $passportnumber;

        // Clear session data
        unset($_SESSION['selected_flight']);
        unset($_SESSION['ticket_type']);
        unset($_SESSION['ticket_price']);

        echo "<script>alert('✓ Ticket Booked Successfully')</script>";
        echo "<script>hideLoading(); window.location='reviewticket.php'</script>";

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('⚠ Booking Failed: " . addslashes($e->getMessage()) . "')</script>";
        echo "<script>hideLoading(); window.location='Passenger_Details.html'</script>";
    }
}
?>
