# Airline Management System - DBMS Project

<p>
  <h3>Built Using:</h3>
  <p>
    <img src="https://img.shields.io/badge/-HTML5-E34F26?style=flat-square&logo=HTML5&logoColor=white">
    <img src="https://img.shields.io/badge/-CSS3-1572B6?style=flat-square&logo=CSS3&logoColor=white">
    <img src="https://img.shields.io/badge/-JavaScript-F7DF1E?style=flat-square&logo=JavaScript&logoColor=white">
    <img src="https://img.shields.io/badge/-php-777BB4?style=flat-square&logo=Php&logoColor=white">
    <img src="https://img.shields.io/badge/-MySQL-4479A1?style=flat-square&logo=MySQL&logoColor=white">
  </p>
</p>

## Project Overview
A secure, modern, and feature-rich Airline Management System built with PHP and MySQL. The system allows administrators to manage flights, airlines, and airports, while passengers can search for flights, book tickets, and manage their bookings.

## New Features in v2.0
- **Responsive Design**: Mobile-friendly interface that works on all devices
- **Dark Mode**: Toggle between light and dark themes with automatic persistence
- **Loading Indicators**: Visual feedback during database operations
- **User-Friendly Error Messages**: Clear, actionable error messages with icons
- **PDF Ticket Generation**: Professional PDF ticket download (optional, requires TCPDF)
- **Modern UI/UX**: Clean, professional interface with improved usability

## Security Features
- **SQL Injection Protection**: All database queries use prepared statements (mysqli_prepare)
- **Password Hashing**: Admin passwords securely hashed using bcrypt
- **Input Validation**: All user inputs validated and sanitized
- **XSS Prevention**: Output escaped using htmlspecialchars()
- **Foreign Key Constraints**: Database integrity enforced with proper relationships
- **Transaction Management**: Database operations use transactions for consistency
- **Session Management**: Secure session handling for user state

## Quick Setup (5 Minutes)

### Step 1: Import Database Schema
1. Install XAMPP (or any PHP/MySQL server)
2. Start XAMPP (Apache and MySQL services)
3. Open phpMyAdmin in your browser
4. Create a new database named `dbms`
5. Import the `dbms.sql` file

### Step 2: Set Admin Password (Required)
After importing the database, you MUST set the admin password:
1. Open your browser and visit: `update_admin_password.php`
2. You'll see "Admin Password Set Successfully!"
3. Your credentials are now: `admin` / `admin123`

### Step 3: TCPDF Installation (Optional)
For PDF ticket download feature:
```bash
cd "HTML, PHP Files"
composer require tecnickcom/tcpdf
```

For detailed setup instructions, see [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md)

## Database Schema
The database includes the following tables with proper relationships:
- `admin` - Administrator accounts with hashed passwords
- `airline` - Airline information
- `city` - City/Location data
- `airport` - Airport details linked to cities
- `flight` - Flight information linked to airlines
- `passenger` - Passenger details
- `ticket` - Ticket bookings linked to passengers and flights

## Default Credentials
- **Admin Username**: `admin`
- **Admin Password**: `admin123`

## Features

### For Administrators
- Add, view, modify, and delete flights
- Manage airline information
- View all flights in the system
- Modify flight details (departure, arrival, duration, prices)

### For Passengers
- Search flights by source, destination, and date
- View available flights with different class options (Business, Economy, Student, Differently Abled)
- Book tickets with passenger details
- View and print/download e-tickets
- Modify passenger details
- Cancel bookings

## Important Notes
- The system uses session management for state persistence
- Temporary tables have been replaced with session variables
- All prices stored as decimal values for accuracy
- Passport numbers must be exactly 8 characters
- Phone numbers must be exactly 10 digits
- Flight codes must be exactly 10 characters
- Theme preference is saved in localStorage

## Security Recommendations for Production
- Change the default admin password immediately
- Use HTTPS for all connections
- Implement rate limiting for login attempts
- Add CSRF protection for forms
- Enable database encryption at rest
- Regularly update PHP and MySQL versions
- Implement proper logging and monitoring

## Troubleshooting
- If you encounter database connection errors, check your XAMPP MySQL service
- Ensure the database name is exactly `dbms` (case-sensitive)
- After importing database, visit `update_admin_password.php` to set admin password
- Check PHP error logs for detailed error messages
- If PDF download fails, ensure TCPDF is installed

## Version History
- **v2.0** - Complete security overhaul, responsive design, dark mode, loading indicators, PDF generation
- **v1.0** - Initial version with basic functionality
