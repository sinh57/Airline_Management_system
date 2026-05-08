<?php
require_once "dbconnection.php";

// Get all airlines using prepared statement
$stmt = mysqli_prepare($con, "SELECT AIRLINE_ID, AIRLINE_NAME FROM airline");
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Airlines ID Reference</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<nav class="navbar">
		<ul>
			<li class="active"><a href="#">Airlines Code</a></li>
			<li><a href="admin_form.html">Add Flights</a></li>
			<li><a href="homepage.html">Home</a></li>
		</ul>
	</nav>
	<div class="hero">
		<h1>AIRLINES ID REFERENCE</h1>
	</div>
	<div class="table-container">
		<table>
			<tr>
				<th>Airline ID</th>
				<th>Airline Name</th>
			</tr>
		<?php
			$rowscount = mysqli_num_rows($query);
			if($rowscount > 0){
				while($rows = mysqli_fetch_array($query)){
		?>
			<tr>
				<td><?php echo htmlspecialchars($rows['AIRLINE_ID']) ?></td>
				<td><?php echo htmlspecialchars($rows['AIRLINE_NAME']) ?></td>	
			</tr>
		<?php	
				}
			} else {
				echo "<tr><td colspan='2'>No airlines found</td></tr>";
			}
			mysqli_stmt_close($stmt);
		?>
		</table>
	</div>
	<script src="theme-toggle.js"></script>
</body>
</html>