<?php
// Start the session to manage logout functionality
session_start();

// Handle logout if the logout button is clicked
if (isset($_POST['logout'])) {
    session_unset();  // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php");  // Redirect to index.php after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homestay Manager Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4ed; /* Light nature background */
            margin: 0;
            padding: 0;
            color: #2d2d2a;
        }

        header {
            background-color: #4caf50; /* Green for nature vibe */
            color: white;
            padding: 15px;
            text-align: center;
        }

        .logout-btn {
            background-color: #e53935; /* Red for logout button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card-header {
            background-color: #8bc34a; /* Light green */
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 18px;
        }

        .card-body {
            padding: 15px;
        }

        /* Button Styling for Links */
        .card-body a {
            display: inline-block;
            background-color: #4caf50; /* Green for button */
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
        }

        .card-body a:hover {
            background-color: #388e3c;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #4caf50;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Homestay Manager Dashboard</h1>
        <p>Welcome to your nature-inspired management portal</p>

        <!-- Logout Button -->
        <form method="post">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </header>

    <div class="dashboard-container">

        <!-- Manage Properties -->
         <div class="card">
             <div class="card-header">Manage Properties</div>
             <div class="card-body">
                  <p>View current properties and add a new one if wanted  </p>
                 <a href="manage_properties.php">Manage Properties</a>
            </div>
        </div>

        <!-- Bookings Section -->
        <div class="card">
            <div class="card-header">Manage Bookings</div>
            <div class="card-body">
                <p>View and manage customer bookings. Check availability and status of rooms.</p>
                <a href="manage_bookings.php">Go to Bookings</a>
            </div>
        </div>

        <!-- Approve Payments -->
        <div class="card">
             <div class="card-header">Approve Payments</div>
             <div class="card-body">
                  <p>Review uploaded receipts and approve or reject pending payments from guests.</p>
                 <a href="Booking/approve_payments.php">Manage Payments</a>
            </div>
        </div>

         <!-- View Customer Stay at Homestay -->
         <div class="card">
             <div class="card-header">View Customer</div>
             <div class="card-body">
                  <p>View Customer that has been stayed at the homestay </p>
                 <a href="Booking/view_customer_stayed.php">View Customer Stayed</a>
            </div>
        </div>

         <!-- View Customer Full Details -->
         <div class="card">
             <div class="card-header">Customer Details</div>
             <div class="card-body">
                  <p>View Customer details and making remarks regarding them </p>
                 <a href="customer_list.php">View Customer Details</a>
            </div>
        </div>

        <!-- Maintenance Requests -->
        <div class="card">
            <div class="card-header">Maintenance Requests</div>
            <div class="card-body">
                <p>Track maintenance requests and ensure smooth operations.</p>
                <a href="Maintenance/room_list.php">View Maintenance</a>
            </div>
        </div>

        <!-- Sales Reports -->
        <div class="card">
            <div class="card-header">Sales Reports</div>
            <div class="card-body">
                <p>Analyze sales and revenue data to track business growth.</p>
                <a href="sales_report.php">View Reports</a>
            </div>
        </div>

        <!-- Guest Reviews -->
        <div class="card">
            <div class="card-header">Guest Reviews</div>
            <div class="card-body">
                <p>Read and manage guest feedback to improve service quality.</p>
                <a href="check_reviews.php">Read Reviews</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Waste Collection</div>
            <div class="card-body">
                <p> Oversee and optimize waste collection schedules</p>
                <a href="waste_collection_home.php">View Waste Collection</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Homestay Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
