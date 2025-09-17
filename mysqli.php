<?php

DEFINE('DB_USER', 'root'); // MySQL username
DEFINE('DB_PASSWORD', ''); // MySQL password (leave empty if none)
DEFINE('DB_HOST', 'localhost'); // MySQL server (localhost)
DEFINE('DB_NAME', 'bookingsungaiserai'); // Database name

// Make the MySQL connection
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if connection was successful
if (!$dbc) {
    // Display detailed error message if connection fails
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}

// Optionally, you can uncomment these lines to verify the connection
// echo "<p>Successfully connected to MySQL</p>\n";
// echo "<p>Database name = " . DB_NAME . "</p>\n";

?>
