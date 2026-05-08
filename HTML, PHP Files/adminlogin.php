<?php
$error = "";
require_once "dbconnection.php";

if(isset($_POST['submit'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Input validation
    if(empty($username) || empty($password)){
        echo "<script>alert('⚠ Please enter both username and password')</script>";
        echo "<script>window.location='adminlogin.php'</script>";
        exit();
    }
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT ADMIN_ID, USERNAME, PASSWORD FROM admin WHERE USERNAME = ?");
    if($stmt){
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($result)){
            // Verify password using password_verify
            if(password_verify($password, $row['PASSWORD'])){
                // Start session and store admin info
                session_start();
                $_SESSION['admin_id'] = $row['ADMIN_ID'];
                $_SESSION['admin_username'] = $row['USERNAME'];
                echo "<script>alert('✓ Login Successful')</script>";
                echo "<script>hideLoading(); window.location='adminchoice.html'</script>";
            } else {
                echo "<script>alert('✗ Invalid username or password. Please try again.')</script>";
                echo "<script>hideLoading();</script>";
            }
        } else {
            echo "<script>alert('✗ Invalid username or password. Please try again.')</script>";
            echo "<script>hideLoading();</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('⚠ Database error. Please try again later.')</script>";
        echo "<script>window.location='adminlogin.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<nav class="navbar">
		<ul>
			<li class="active"><a href="#">Admin Login</a></li>
			<li><a href="homepage.html">Home</a></li>
		</ul>
	</nav>
	<div class="hero">
		<h1>ADMIN LOGIN</h1>
	</div>
	<div class="form-container">
		<form method="post" onsubmit="showLoading('Authenticating...')">
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="Enter username" required>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Enter password" required>
			</div>
			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>
	<script src="theme-toggle.js"></script>
</body>
</html>