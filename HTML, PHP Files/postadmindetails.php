<?php 
require_once "dbconnection.php";

if(isset($_POST['submit'])){
    // Get and sanitize input
    $country = trim($_POST['country']);
    $state = trim($_POST['state']);
    $city = trim($_POST['source']);
    $airportname = trim($_POST['airportname']);
    $source = trim($_POST['source']);
    $destination = trim($_POST['destination']);
    $departure = trim($_POST['departure']);
    $arrival = trim($_POST['arrival']);
    $duration = trim($_POST['duration']);
    $airlinesid = trim($_POST['airlinesid']);
    $flightcode = strtoupper(trim($_POST['flightcode']));
    $date = trim($_POST['date']);
    $economyclass = floatval($_POST['economyclass']);
    $businessclass = floatval($_POST['businessclass']);
    $students = floatval($_POST['students']);
    $diff = floatval($_POST['diff']);
    
    // Input validation
    if(empty($country) || empty($state) || empty($city) || empty($airportname) || 
       empty($source) || empty($destination) || empty($departure) || empty($arrival) || 
       empty($duration) || empty($airlinesid) || empty($flightcode) || empty($date)){
        echo "<script>alert('Please fill all required fields')</script>";
        echo "<script>window.location='admin_form.html'</script>";
        exit();
    }
    
    // Validate flight code length
    if(strlen($flightcode) != 10){
        echo "<script>alert('Flight Code should be exactly 10 characters')</script>";
        echo "<script>window.location='admin_form.html'</script>";
        exit();
    }
    
    // Validate prices
    if($economyclass < 0 || $businessclass < 0 || $students < 0 || $diff < 0){
        echo "<script>alert('Prices cannot be negative')</script>";
        echo "<script>window.location='admin_form.html'</script>";
        exit();
    }
    
    // Check if airline exists
    $stmt = mysqli_prepare($con, "SELECT AIRLINE_ID FROM airline WHERE AIRLINE_ID = ?");
    mysqli_stmt_bind_param($stmt, "s", $airlinesid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0){
        echo "<script>alert('Airline Code not in database')</script>";
        echo "<script>window.location='admin_form.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Check if flight code already exists
    $stmt = mysqli_prepare($con, "SELECT FLIGHT_CODE FROM flight WHERE FLIGHT_CODE = ?");
    mysqli_stmt_bind_param($stmt, "s", $flightcode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('Duplicate Flight Code !')</script>";
        echo "<script>window.location='admin_form.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Start transaction
    mysqli_begin_transaction($con);
    
    try {
        // Insert city if not exists
        $stmt = mysqli_prepare($con, "INSERT IGNORE INTO city (C_NAME, STATE, COUNTRY) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $city, $state, $country);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Insert airport
        $stmt = mysqli_prepare($con, "INSERT INTO airport (A_NAME, STATE, COUNTRY, C_NAME) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $airportname, $state, $country, $city);
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to insert airport");
        }
        mysqli_stmt_close($stmt);
        
        // Insert flight
        $stmt = mysqli_prepare($con, "INSERT INTO flight (SOURCE, DESTINATION, DEPARTURE, ARRIVAL, DURATION, FLIGHT_CODE, AIRLINE_ID, PRICE_BUSINESS, PRICE_ECONOMY, PRICE_STUDENTS, PRICE_DIFFERENTLYABLED, DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssssddddd", $source, $destination, $departure, $arrival, $duration, $flightcode, $airlinesid, $businessclass, $economyclass, $students, $diff, $date);
        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Failed to insert flight");
        }
        mysqli_stmt_close($stmt);
        
        // Commit transaction
        mysqli_commit($con);
        
        echo "<script>alert('Inserted successfully')</script>";
        echo "<script>window.location='homepage.html'</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Insertion Failed: " . addslashes($e->getMessage()) . "')</script>";
        echo "<script>window.location='admin_form.html'</script>";
    }
}
?>