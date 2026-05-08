<?php 
require_once "dbconnection.php";
session_start();

if(isset($_POST['submit'])){
    $catch = strtoupper(trim($_POST['flightcode']));
    
    // Validate flight code
    if(empty($catch) || strlen($catch) != 10){
        echo "<script>alert('Invalid Flight Code')</script>";
        echo "<script>window.location='modifyadmindetails.html'</script>";
        exit();
    }
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT DURATION, ARRIVAL, DEPARTURE, PRICE_ECONOMY, PRICE_BUSINESS, PRICE_STUDENTS, PRICE_DIFFERENTLYABLED FROM flight WHERE FLIGHT_CODE = ?");
    mysqli_stmt_bind_param($stmt, "s", $catch);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($res) == 0){
        echo "<script>alert('Flight Code not in database')</script>";
        echo "<script>window.location='modifyadmindetails.html'</script>";
        mysqli_stmt_close($stmt);
        exit();
    }
    
    $row = mysqli_fetch_assoc($res);
    $duration = $row['DURATION'];
    $arrival = $row['ARRIVAL'];
    $departure = $row['DEPARTURE'];
    $economyclass = $row['PRICE_ECONOMY'];
    $businessclass = $row['PRICE_BUSINESS'];
    $students = $row['PRICE_STUDENTS'];
    $diff = $row['PRICE_DIFFERENTLYABLED'];
    mysqli_stmt_close($stmt);
    
    // Store flight code in session instead of temporary table
    $_SESSION['selected_flight'] = $catch;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modify Flight Details</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<nav class="navbar">
		<ul>
			<li class="active"><a href="#">Modify Flight Details</a></li>
			<li><a href="adminchoice.html">Admin</a></li>
			<li><a href="homepage.html">Home</a></li>
		</ul>
	</nav>
	<div class="hero">
		<h1>MODIFY FLIGHT DETAILS</h1>
	</div>
	<div class="form-container">
		<form action="modifyflightdetails.php" method="post" onsubmit="showLoading('Updating flight...')">
			<div class="form-group">
				<label for="departure">Departure Time</label>
				<input type="text" id="departure" name="departure" value="<?php echo htmlspecialchars($departure); ?>" placeholder="e.g., 10:00 AM">
			</div>
			<div class="form-group">
				<label for="arrival">Arrival Time</label>
				<input type="text" id="arrival" name="arrival" value="<?php echo htmlspecialchars($arrival); ?>" placeholder="e.g., 02:00 PM">
			</div>
			<div class="form-group">
				<label for="duration">Duration</label>
				<input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($duration); ?>" placeholder="e.g., 4h">
			</div>
			<h3 style="margin: 20px 0; color: var(--text-primary);">Pricing</h3>
			<div class="form-group">
				<label for="businessclass">Business Class Price</label>
				<input type="number" id="businessclass" name="businessclass" value="<?php echo htmlspecialchars($businessclass); ?>" step="0.01">
			</div>
			<div class="form-group">
				<label for="economyclass">Economy Class Price</label>
				<input type="number" id="economyclass" name="economyclass" value="<?php echo htmlspecialchars($economyclass); ?>" step="0.01">
			</div>
			<div class="form-group">
				<label for="students">Student Price</label>
				<input type="number" id="students" name="students" value="<?php echo htmlspecialchars($students); ?>" step="0.01">
			</div>
			<div class="form-group">
				<label for="diff">Differently Abled Price</label>
				<input type="number" id="diff" name="diff" value="<?php echo htmlspecialchars($diff); ?>" step="0.01">
			</div>
			<button type="submit" class="btn btn-primary">Update Flight</button>
		</form>
	</div>
	<script src="theme-toggle.js"></script>
</body>
</html>