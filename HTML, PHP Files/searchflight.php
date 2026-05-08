<?php 
require_once "dbconnection.php";
session_start();

if(isset($_POST['search'])){
    if(isset($_POST['source']) && !empty($_POST['source'])){	
        if(isset($_POST['destination']) && !empty($_POST['destination'])){
            if(isset($_POST['date']) && !empty($_POST['date'])){
                $source = trim($_POST['source']);
                $destination = trim($_POST['destination']);
                $date = trim($_POST['date']);
                
                // Use prepared statement to prevent SQL injection
                $stmt = mysqli_prepare($con, "SELECT ARRIVAL, DEPARTURE, DURATION, FLIGHT_CODE, AIRLINE_ID, PRICE_BUSINESS, PRICE_ECONOMY, PRICE_STUDENTS, PRICE_DIFFERENTLYABLED FROM flight WHERE SOURCE = ? AND DESTINATION = ? AND DATE = ?");
                mysqli_stmt_bind_param($stmt, "sss", $source, $destination, $date);
                mysqli_stmt_execute($stmt);
                $query = mysqli_stmt_get_result($stmt);
                $rowscount = mysqli_num_rows($query);
                
                if ($rowscount == 0){
                    echo "<script>alert('No Flights available')</script>";
                    echo "<script>window.location='searchflight.html'</script>";
                    exit();
                }
            }
            else{
                echo "<script>alert('Please Enter the details correctly')</script>";
                echo "<script>window.location='homepage.html'</script>";
                exit();
            }
        }
        else{
            echo "<script>alert('Please Enter the details correctly')</script>";
            echo "<script>window.location='homepage.html'</script>";
            exit();
        }
    }
    else{
        echo "<script>alert('Please Enter the details correctly')</script>";
        echo "<script>window.location='homepage.html'</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Available Flights</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<nav class="navbar">
		<ul>
			<li class="active"><a href="#">Available Flights</a></li>
			<li><a href="homepage.html">Home</a></li>
		</ul>
	</nav>
	<div class="hero">
		<h1>AVAILABLE FLIGHTS</h1>
	</div>
	<div class="table-container">
		<table>
			<tr>
				<th>Departure</th>
				<th>Arrival</th>
				<th>Duration</th>
				<th>Flight Code</th>
				<th>Airline ID</th>
				<th>Price</th>
				<th>Type</th>
				<th>Action</th>
			</tr>
		<?php 
			mysqli_data_seek($query, 0);
			while($rows = mysqli_fetch_array($query)){
		?>
			<tr>
				<td><?php echo htmlspecialchars($rows['DEPARTURE']) ?></td>
				<td><?php echo htmlspecialchars($rows['ARRIVAL']) ?></td>
				<td><?php echo htmlspecialchars($rows['DURATION']) ?></td>
				<td><?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?></td>
				<td><?php echo htmlspecialchars($rows['AIRLINE_ID']) ?></td>
				<td>$<?php echo number_format($rows['PRICE_BUSINESS'], 2) ?></td>
				<td>Business</td>
				<td><a href="postflightcodebusiness.php?id=<?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?>" class="btn btn-primary">Select</a></td>
			</tr>
			<tr>
				<td><?php echo htmlspecialchars($rows['DEPARTURE']) ?></td>
				<td><?php echo htmlspecialchars($rows['ARRIVAL']) ?></td>
				<td><?php echo htmlspecialchars($rows['DURATION']) ?></td>
				<td><?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?></td>
				<td><?php echo htmlspecialchars($rows['AIRLINE_ID']) ?></td>
				<td>$<?php echo number_format($rows['PRICE_ECONOMY'], 2) ?></td>
				<td>Economy</td>
				<td><a href="postflightcodeeconomy.php?id=<?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?>" class="btn btn-primary">Select</a></td>
			</tr>
			<tr>
				<td><?php echo htmlspecialchars($rows['DEPARTURE']) ?></td>
				<td><?php echo htmlspecialchars($rows['ARRIVAL']) ?></td>
				<td><?php echo htmlspecialchars($rows['DURATION']) ?></td>
				<td><?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?></td>
				<td><?php echo htmlspecialchars($rows['AIRLINE_ID']) ?></td>
				<td>$<?php echo number_format($rows['PRICE_STUDENTS'], 2) ?></td>
				<td>Student</td>
				<td><a href="postflightcodestudents.php?id=<?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?>" class="btn btn-primary">Select</a></td>
			</tr>
			<tr>
				<td><?php echo htmlspecialchars($rows['DEPARTURE']) ?></td>
				<td><?php echo htmlspecialchars($rows['ARRIVAL']) ?></td>
				<td><?php echo htmlspecialchars($rows['DURATION']) ?></td>
				<td><?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?></td>
				<td><?php echo htmlspecialchars($rows['AIRLINE_ID']) ?></td>
				<td>$<?php echo number_format($rows['PRICE_DIFFERENTLYABLED'], 2) ?></td>
				<td>Differently Abled</td>
				<td><a href="postflightcodediff.php?id=<?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?>" class="btn btn-primary">Select</a></td>
			</tr>
		<?php	
			}
			mysqli_stmt_close($stmt);
		?>
		</table>
	</div>
	<script src="theme-toggle.js"></script>
</body>
</html>