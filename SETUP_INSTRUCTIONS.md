# Airline Management System - Complete Setup Guide

## Quick Setup (5 Minutes)

Follow these 3 steps to get your project running 100%:

### Step 1: Import Database Schema (Required)
1. Open phpMyAdmin or your MySQL client
2. Create a new database named `dbms`
3. Import the `dbms.sql` file into the database
4. The schema includes:
   - Foreign key constraints for data integrity
   - Proper primary keys on all tables
   - Removed redundant temporary tables
   - Enhanced security with proper data types and constraints

### Step 2: Set Admin Password (Required - Do This Immediately After Import)
After importing the database, you MUST set the admin password:

1. Open your browser and visit:
   ```
   http://localhost/your-project-folder/update_admin_password.php
   ```
2. You will see a success message: "Admin Password Set Successfully!"
3. Your admin credentials are now:
   - **Username:** admin
   - **Password:** admin123
4. Click "Go to Admin Login" to proceed

**Why this step is required:** The database uses bcrypt password hashing for security. The update script generates the correct hash for "admin123" and stores it in the database.

### Step 3: TCPDF Installation (Optional - For PDF Download Feature)
If you want users to download tickets as PDF files:

**Option A: Using Composer (Recommended)**
```bash
cd "HTML, PHP Files"
composer require tecnickcom/tcpdf
```

**Option B: Manual Download**
1. Download TCPDF from: https://github.com/tecnickcom/tcpdf/releases
2. Extract the zip file
3. Copy the `tcpdf` folder to: `HTML, PHP Files/tcpdf/`
4. Ensure the file exists at: `HTML, PHP Files/tcpdf/tcpdf.php`

**Note:** If TCPDF is not installed, the system still works perfectly. Users can print tickets using the browser's print function instead of downloading PDF.

## File Structure

```
Airline_Management_system/
├── HTML, PHP Files/
│   ├── styles.css          # Responsive CSS with dark mode
│   ├── theme-toggle.js     # Theme toggle and loading indicators
│   ├── dbconnection.php    # Database connection
│   ├── homepage.html       # Main entry point
│   ├── adminlogin.php      # Admin login with password hashing
│   ├── passengerchoice.html
│   ├── searchflight.html
│   ├── searchflight.php
│   ├── Passenger_Details.html
│   ├── postpassengerdetails.php
│   ├── reviewticket.php    # PDF ticket generation
│   └── ... (other PHP files)
└── dbms.sql               # Database schema
```

## Optional: PDF Generation

To enable PDF ticket generation:

1. **Install TCPDF Library**
   ```bash
   cd "HTML, PHP Files"
   composer require tecnickcom/tcpdf
   ```
   OR manually download from: https://github.com/tecnickcom/tcpdf

2. **Place TCPDF in the project**
   - Ensure TCPDF is accessible at: `HTML, PHP Files/tcpdf/tcpdf.php`

3. **If TCPDF is not installed**
   - The system will still work
   - PDF download will show an error message
   - Users can still print tickets using the browser's print function

## Features Implemented

### Security
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS prevention (htmlspecialchars on output)
- ✅ Password hashing (bcrypt)
- ✅ Session management
- ✅ Input validation
- ✅ Transaction support for data integrity

### UI/UX Enhancements
- ✅ Responsive design (mobile-friendly)
- ✅ Dark mode with theme toggle
- ✅ Loading indicators for database operations
- ✅ User-friendly error messages
- ✅ Professional PDF ticket generation (optional)
- ✅ Modern, clean interface

### Functionality
- ✅ Admin login with secure authentication
- ✅ Flight search and booking
- ✅ Multiple ticket types (Business, Economy, Student, Differently Abled)
- ✅ Passenger management
- ✅ Flight management (add, modify, delete)
- ✅ Ticket review and printing
- ✅ Airline reference

## Testing Checklist

Before going live, test:

1. **Admin Login**
   - [ ] Login with admin/admin123
   - [ ] Add a new flight
   - [ ] View all flights
   - [ ] Modify a flight
   - [ ] Delete a flight

2. **Customer Flow**
   - [ ] Search for flights
   - [ ] Select a flight type
   - [ ] Enter passenger details
   - [ ] Complete booking
   - [ ] View ticket
   - [ ] Print/Download PDF (if TCPDF installed)

3. **Responsive Design**
   - [ ] Test on mobile device (viewport < 480px)
   - [ ] Test on tablet (viewport < 768px)
   - [ ] Test on desktop

4. **Dark Mode**
   - [ ] Toggle theme button works
   - [ ] Theme persists on page reload
   - [ ] All pages display correctly in dark mode

5. **Error Handling**
   - [ ] Invalid login shows error
   - [ ] Missing fields show error
   - [ ] Duplicate passport shows error
   - [ ] No flights found shows error

## Default Credentials

**Admin:**
- Username: `admin`
- Password: `admin123`

## Troubleshooting

### Admin login not working
- Ensure the admin table has the hashed password
- Check if password_verify is working correctly
- Verify dbconnection.php settings

### PDF generation not working
- Ensure TCPDF library is installed
- Check file path: `HTML, PHP Files/tcpdf/tcpdf.php`
- Enable error reporting to see specific errors

### Database connection issues
- Check dbconnection.php credentials
- Ensure MySQL server is running
- Verify database name is `dbms`

### Session issues
- Ensure session_start() is called at the beginning of PHP files
- Check PHP session configuration
- Clear browser cookies if needed

## Production Recommendations

1. **Security**
   - Change default admin password immediately
   - Use HTTPS in production
   - Implement rate limiting for login attempts
   - Add CSRF protection for forms
   - Use environment variables for database credentials

2. **Performance**
   - Add database indexes for frequently queried columns
   - Implement caching for static data
   - Optimize images and assets
   - Use CDN for CSS/JS libraries

3. **Backup**
   - Regular database backups
   - File system backups
   - Automated backup scripts

4. **Monitoring**
   - Error logging
   - User activity logging
   - Performance monitoring

## Support

For issues or questions:
- Check the README.md for general information
- Review code comments for specific implementation details
- Check browser console for JavaScript errors
- Check PHP error logs for server-side errors
