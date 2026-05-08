<?php
/**
 * Admin Password Update Script
 * 
 * This script generates a bcrypt hash for the admin password and updates the database.
 * Run this file once after importing the database schema to set the admin password.
 * 
 * Default credentials: admin / admin123
 */

require_once "dbconnection.php";

// The password to hash
$password = "admin123";

// Generate bcrypt hash
$hash = password_hash($password, PASSWORD_BCRYPT);

// Update the admin table
$stmt = mysqli_prepare($con, "UPDATE admin SET PASSWORD = ? WHERE USERNAME = 'admin'");
mysqli_stmt_bind_param($stmt, "s", $hash);

if(mysqli_stmt_execute($stmt)){
    // Success - show HTML page with success message
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Password Updated</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <nav class="navbar">
            <ul>
                <li><a href="homepage.html">Home</a></li>
            </ul>
        </nav>
        <div class="hero">
            <h1>✓ PASSWORD UPDATED</h1>
        </div>
        <div class="form-container" style="text-align: center;">
            <div style="padding: 30px; background: #d4edda; border-radius: 8px; margin-bottom: 20px;">
                <h2 style="color: #155724; margin-bottom: 15px;">Admin Password Set Successfully!</h2>
                <p style="color: #155724; margin-bottom: 10px;"><strong>Username:</strong> admin</p>
                <p style="color: #155724; margin-bottom: 20px;"><strong>Password:</strong> admin123</p>
                <a href="adminlogin.php" class="btn btn-success">Go to Admin Login</a>
            </div>
            <p style="color: #666; font-size: 14px;">You can now login with the credentials above.</p>
        </div>
        <script src="theme-toggle.js"></script>
    </body>
    </html>
    <?php
} else {
    // Error - show HTML page with error message
    $error = mysqli_error($con);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <nav class="navbar">
            <ul>
                <li><a href="homepage.html">Home</a></li>
            </ul>
        </nav>
        <div class="hero">
            <h1>✗ ERROR</h1>
        </div>
        <div class="form-container" style="text-align: center;">
            <div style="padding: 30px; background: #f8d7da; border-radius: 8px; margin-bottom: 20px;">
                <h2 style="color: #721c24; margin-bottom: 15px;">Failed to Update Password</h2>
                <p style="color: #721c24; margin-bottom: 20px;">Error: <?php echo htmlspecialchars($error); ?></p>
                <a href="homepage.html" class="btn btn-danger">Return to Home</a>
            </div>
        </div>
        <script src="theme-toggle.js"></script>
    </body>
    </html>
    <?php
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
