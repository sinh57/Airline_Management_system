<?php
require_once "dbconnection.php";
session_start();

// Get the most recent ticket for the current session
$passportno = isset($_SESSION['last_passport']) ? $_SESSION['last_passport'] : '';

if(empty($passportno)){
    echo "<script>alert('⚠ No booking found. Please book a ticket first.')</script>";
    echo "<script>window.location='homepage.html'</script>";
    exit();
}

// Use prepared statements to prevent SQL injection
$stmt = mysqli_prepare($con, "SELECT t.TICKET_NO, t.SOURCE, t.DESTINATION, t.DATE_OF_TRAVEL, t.PRICE, t.TYPE, t.FLIGHT_CODE, p.FNAME, p.MNAME, p.LNAME, p.AGE, p.SEX, p.PHONE, p.ADDRESS FROM ticket t JOIN passenger p ON t.PASSPORT_NO = p.PASSPORT_NO WHERE t.PASSPORT_NO = ? ORDER BY t.TICKET_NO DESC LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $passportno);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){
    echo "<script>alert('⚠ No ticket found for this passenger')</script>";
    echo "<script>window.location='homepage.html'</script>";
    mysqli_stmt_close($stmt);
    exit();
}

$row = mysqli_fetch_assoc($result);
$ticketno = $row['TICKET_NO'];
$source = $row['SOURCE'];
$destination = $row['DESTINATION'];
$date = $row['DATE_OF_TRAVEL'];
$price = $row['PRICE'];
$type = $row['TYPE'];
$flight = $row['FLIGHT_CODE'];
$fname = $row['FNAME'];
$mname = $row['MNAME'];
$lname = $row['LNAME'];
$age = $row['AGE'];
$sex = $row['SEX'];
$phone = $row['PHONE'];
$address = $row['ADDRESS'];
mysqli_stmt_close($stmt);

// Get flight details
$stmt = mysqli_prepare($con, "SELECT ARRIVAL, DEPARTURE, DURATION, AIRLINE_ID FROM flight WHERE FLIGHT_CODE = ?");
mysqli_stmt_bind_param($stmt, "s", $flight);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){
    echo "<script>alert('⚠ Flight not found')</script>";
    echo "<script>window.location='homepage.html'</script>";
    mysqli_stmt_close($stmt);
    exit();
}

$row = mysqli_fetch_assoc($result);
$arrival = $row['ARRIVAL'];
$departure = $row['DEPARTURE'];
$duration = $row['DURATION'];
$airlineid = $row['AIRLINE_ID'];
mysqli_stmt_close($stmt);

// Get airline name
$stmt = mysqli_prepare($con, "SELECT AIRLINE_NAME FROM airline WHERE AIRLINE_ID = ?");
mysqli_stmt_bind_param($stmt, "s", $airlineid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){
    echo "<script>alert('⚠ Airline not found')</script>";
    echo "<script>window.location='homepage.html'</script>";
    mysqli_stmt_close($stmt);
    exit();
}

$row = mysqli_fetch_assoc($result);
$airlinename = $row['AIRLINE_NAME'];
mysqli_stmt_close($stmt);

// Clear session
unset($_SESSION['last_passport']);

// Check if PDF generation is requested
if(isset($_GET['download']) && $_GET['download'] == 'pdf'){
    // Try to use TCPDF if available
    $tcpdf_path = __DIR__ . '/tcpdf/tcpdf.php';
    if(file_exists($tcpdf_path)){
        require_once($tcpdf_path);
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Airline Management System');
        $pdf->SetAuthor('Airline Management System');
        $pdf->SetTitle('E-Ticket - ' . $ticketno);
        
        // Set header and footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Build ticket HTML
        $html = '
        <style>
            .ticket-container {
                border: 2px solid #333;
                padding: 20px;
                margin: 10px;
            }
            .ticket-header {
                text-align: center;
                border-bottom: 2px solid #333;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            .ticket-header h1 {
                color: #007bff;
                font-size: 24px;
                margin: 0;
            }
            .ticket-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            .info-row {
                margin-bottom: 10px;
            }
            .info-label {
                font-weight: bold;
                color: #555;
            }
            .info-value {
                color: #333;
            }
            .ticket-footer {
                margin-top: 20px;
                text-align: center;
                border-top: 2px solid #333;
                padding-top: 15px;
            }
        </style>
        
        <div class="ticket-container">
            <div class="ticket-header">
                <h1>✈️ E-TICKET</h1>
                <p style="font-size: 14px;">' . htmlspecialchars($airlinename) . '</p>
            </div>
            
            <div class="ticket-info">
                <div class="info-row">
                    <span class="info-label">Ticket Number:</span>
                    <span class="info-value">' . htmlspecialchars($ticketno) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Flight Code:</span>
                    <span class="info-value">' . htmlspecialchars($flight) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Passenger Name:</span>
                    <span class="info-value">' . htmlspecialchars($fname . ' ' . ($mname ? $mname . ' ' : '') . $lname) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Passport Number:</span>
                    <span class="info-value">' . htmlspecialchars($passportno) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Source:</span>
                    <span class="info-value">' . htmlspecialchars($source) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Destination:</span>
                    <span class="info-value">' . htmlspecialchars($destination) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure:</span>
                    <span class="info-value">' . htmlspecialchars($departure) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Arrival:</span>
                    <span class="info-value">' . htmlspecialchars($arrival) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Duration:</span>
                    <span class="info-value">' . htmlspecialchars($duration) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Travel:</span>
                    <span class="info-value">' . htmlspecialchars($date) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">' . htmlspecialchars($type) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Price:</span>
                    <span class="info-value">$' . number_format($price, 2) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age:</span>
                    <span class="info-value">' . htmlspecialchars($age) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sex:</span>
                    <span class="info-value">' . htmlspecialchars($sex) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">' . htmlspecialchars($phone) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value">' . htmlspecialchars($address) . '</span>
                </div>
            </div>
            
            <div class="ticket-footer">
                <p>Thank you for choosing ' . htmlspecialchars($airlinename) . '</p>
                <p style="font-size: 10px; color: #666;">This is an electronic ticket. Please keep this document for your records.</p>
            </div>
        </div>';
        
        // Write HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF document
        $pdf->Output('ticket_' . $ticketno . '.pdf', 'D');
        exit();
    } else {
        // TCPDF not available, show error
        echo "<script>alert('⚠ PDF library not available. Please install TCPDF.')</script>";
        echo "<script>window.location='reviewticket.php'</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Ticket</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="homepage.html">Home</a></li>
        </ul>
    </nav>
    
    <div class="ticket">
        <div class="ticket-header">
            <h1>✈️ E-TICKET</h1>
            <p><?php echo htmlspecialchars($airlinename); ?></p>
        </div>
        
        <div class="ticket-info">
            <div class="info-section">
                <label>Ticket Number:</label>
                <span><?php echo htmlspecialchars($ticketno); ?></span>
            </div>
            <div class="info-section">
                <label>Flight Code:</label>
                <span><?php echo htmlspecialchars($flight); ?></span>
            </div>
            <div class="info-section">
                <label>Passenger Name:</label>
                <span><?php echo htmlspecialchars($fname . ' ' . ($mname ? $mname . ' ' : '') . $lname); ?></span>
            </div>
            <div class="info-section">
                <label>Passport Number:</label>
                <span><?php echo htmlspecialchars($passportno); ?></span>
            </div>
            <div class="info-section">
                <label>Source:</label>
                <span><?php echo htmlspecialchars($source); ?></span>
            </div>
            <div class="info-section">
                <label>Destination:</label>
                <span><?php echo htmlspecialchars($destination); ?></span>
            </div>
            <div class="info-section">
                <label>Departure:</label>
                <span><?php echo htmlspecialchars($departure); ?></span>
            </div>
            <div class="info-section">
                <label>Arrival:</label>
                <span><?php echo htmlspecialchars($arrival); ?></span>
            </div>
            <div class="info-section">
                <label>Duration:</label>
                <span><?php echo htmlspecialchars($duration); ?></span>
            </div>
            <div class="info-section">
                <label>Date of Travel:</label>
                <span><?php echo htmlspecialchars($date); ?></span>
            </div>
            <div class="info-section">
                <label>Class:</label>
                <span><?php echo htmlspecialchars($type); ?></span>
            </div>
            <div class="info-section">
                <label>Price:</label>
                <span>$<?php echo number_format($price, 2); ?></span>
            </div>
            <div class="info-section">
                <label>Age:</label>
                <span><?php echo htmlspecialchars($age); ?></span>
            </div>
            <div class="info-section">
                <label>Sex:</label>
                <span><?php echo htmlspecialchars($sex); ?></span>
            </div>
            <div class="info-section">
                <label>Phone:</label>
                <span><?php echo htmlspecialchars($phone); ?></span>
            </div>
            <div class="info-section">
                <label>Address:</label>
                <span><?php echo htmlspecialchars($address); ?></span>
            </div>
        </div>
        
        <div class="ticket-footer">
            <p>Thank you for choosing <?php echo htmlspecialchars($airlinename); ?></p>
            <div style="margin-top: 15px;">
                <button class="print-btn" onclick="window.print()">Print Ticket</button>
                <a href="reviewticket.php?download=pdf" class="btn btn-primary" style="margin-left: 10px;">Download PDF</a>
            </div>
            <br><br>
            <a href="homepage.html">Return to Home</a>
        </div>
    </div>
    <script src="theme-toggle.js"></script>
</body>
</html>
<?php
mysqli_close($con);
?>
