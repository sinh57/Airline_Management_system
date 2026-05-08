<?php
require_once "dbconnection.php";
session_start();

if(isset($_POST['submit'])){
    $passportno = strtoupper(trim($_POST['passportno']));
    $ticketno = intval($_POST['ticketnumber']);
    
    // Validate inputs
    if(empty($passportno) || strlen($passportno) != 8){
        echo "<script>alert('Please enter a valid 8-character passport number')</script>";
        echo "<script>window.location='modifypassengerdetails.html'</script>";
        exit();
    }
    
    if($ticketno <= 0){
        echo "<script>alert('Please enter a valid ticket number')</script>";
        echo "<script>window.location='modifypassengerdetails.html'</script>";
        exit();
    }
    
    // Check if passenger exists using prepared statement
    $stmt = mysqli_prepare($con, "SELECT FNAME, MNAME, LNAME, AGE, SEX, PHONE, ADDRESS FROM passenger WHERE PASSPORT_NO = ?");
    mysqli_stmt_bind_param($stmt, "s", $passportno);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($res) == 0){
        echo "<script>alert('Passenger not found')</script>";
        echo "<script>window.location='modifypassengerdetails.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    
    $row = mysqli_fetch_assoc($res);
    $fname = $row['FNAME'];
    $mname = $row['MNAME'];
    $lname = $row['LNAME'];
    $age = $row['AGE'];
    $sex = $row['SEX'];
    $phone = $row['PHONE'];
    $address = $row['ADDRESS'];
    mysqli_stmt_close($stmt);
    
    // Check if ticket exists and belongs to this passenger
    $stmt = mysqli_prepare($con, "SELECT TICKET_NO FROM ticket WHERE TICKET_NO = ? AND PASSPORT_NO = ?");
    mysqli_stmt_bind_param($stmt, "is", $ticketno, $passportno);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($res) == 0){
        echo "<script>alert('No such ticket found for this passenger')</script>";
        echo "<script>window.location='modifypassengerdetails.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    mysqli_stmt_close($stmt);
    
    // Store passport number in session instead of temporary table
    $_SESSION['selected_passport'] = $passportno;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modify Passenger Details</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<nav class="navbar">
		<ul>
			<li class="active"><a href="#">Modify Passenger Details</a></li>
			<li><a href="homepage.html">Home</a></li>
		</ul>
	</nav>
	<div class="hero">
		<h1>MODIFY PASSENGER DETAILS</h1>
	</div>
	<div class="form-container">
		<form action="modifypassengerdetails.php" method="post" onsubmit="showLoading('Updating passenger...')">
			<div class="form-group">
				<label for="firstname">First Name</label>
				<input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($fname); ?>" placeholder="Enter first name">
			</div>
			<div class="form-group">
				<label for="middlename">Middle Name (Optional)</label>
				<input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($mname); ?>" placeholder="Enter middle name">
			</div>
			<div class="form-group">
				<label for="lastname">Last Name</label>
				<input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lname); ?>" placeholder="Enter last name">
			</div>
			<div class="form-group">
				<label for="age">Age</label>
				<input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" placeholder="Enter age" min="1" max="120">
			</div>
			<div class="form-group">
				<label for="sex">Sex (M/F/O)</label>
				<input type="text" id="sex" name="sex" value="<?php echo htmlspecialchars($sex); ?>" placeholder="M, F, or O" maxlength="1">
			</div>
			<div class="form-group">
				<label for="phonenumber">Phone Number (10 digits)</label>
				<input type="text" id="phonenumber" name="phonenumber" value="<?php echo htmlspecialchars($phone); ?>" placeholder="e.g., 1234567890" maxlength="10">
			</div>
			<div class="form-group">
				<label for="address">Address</label>
				<input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Enter address">
			</div>
			<button type="submit" class="btn btn-primary">Update Details</button>
		</form>
	</div>
	<script src="theme-toggle.js"></script>
</body>
</html>