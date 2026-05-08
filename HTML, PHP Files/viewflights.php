<?php
require_once "dbconnection.php";

// Get all flights using prepared statement
$stmt = mysqli_prepare($con, "SELECT * FROM flight");
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
	<title>All Flights</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<nav class="navbar">
		<ul>
			<li class="active"><a href="#">All Flights</a></li>
			<li><a href="adminchoice.html">Admin</a></li>
			<li><a href="homepage.html">Home</a></li>
		</ul>
	</nav>
	<div class="hero">
		<h1>ALL FLIGHTS</h1>
	</div>
	<div class="table-container">
		<table>
			<tr>
				<th>Source</th>
				<th>Destination</th>
				<th>Departure</th>
				<th>Arrival</th>
				<th>Duration</th>
				<th>Flight Code</th>
				<th>Airline ID</th>
				th>Business</th>
				<th>Economy</th>
				<th>Student</th>
				th>Differently Abled</th>
				th>Date</th>
			</tr>
		<?php
			$rowscount = mysqli_num_rows($query);
			if($rowscount > 0){
				while($rows = mysqli_fetch_array($query)){
		?>
			<tr>
				<td><?php echo htmlspecialchars($rows['SOURCE']) ?></td>
				<td><?php echo htmlspecialchars($rows['DESTINATION']) ?></td>
				<td><?php echo htmlspecialchars($rows['DEPARTURE']) ?></td>
				<td><?php echo htmlspecialchars($rows['ARRIVAL']) ?></td>
				<td><?php echo htmlspecialchars($rows['DURATION']) ?></td>
				<td><?php echo htmlspecialchars($rows['FLIGHT_CODE']) ?></td>
				<td><?php echo htmlspecialchars($rows['AIRLINE_ID']) ?></td>
				<td>$<?php echo number_format($rows['PRICE_BUSINESS'], 2) ?></td>
				<td>$<?php echo number_format($rows['PRICE_ECONOMY'], 2) ?></td>
				<td>$<?php echo number_format($rows['PRICE_STUDENTS'], 2) ?></td>
				<td>$<?php echo number_format($rows['PRICE_DIFFERENTLYABLED'], 2) ?></td>
				<td><?php echo htmlspecialchars($rows['DATE']) ?></td>
			</tr>
		<?php	
			}
			} else {
				echo "<tr><td colspan='12'>No flights found</td></tr>";
			}
			mysqli_stmt_close($stmt);
		?>
		</table>
	</div>
	<script src="theme-toggle.js"></script>
</body>
</html>