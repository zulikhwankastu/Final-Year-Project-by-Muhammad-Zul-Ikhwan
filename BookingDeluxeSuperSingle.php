<?php
// booking_form.php

// Include the database connection
include '_db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $roomNo = $_POST['roomNo'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $remark = $_POST['remark'];

    // Check for required fields
    if (empty($roomNo) || empty($name) || empty($contact) || empty($checkin) || empty($checkout)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check for existing booking with overlapping dates for the same room
        $overlapQuery = "SELECT * FROM deluxesupersingles WHERE roomNo = ? AND ((CheckIn <= ? AND CheckOut >= ?) OR (CheckIn <= ? AND CheckOut >= ?))";
        $stmt = mysqli_prepare($dbc, $overlapQuery);
        mysqli_stmt_bind_param($stmt, 'issss', $roomNo, $checkout, $checkin, $checkout, $checkin);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = 'The selected room is already booked for the specified dates. Please choose different dates or another room.';
        } else {
            // Prepare and execute the insertion query if no overlap found
           // Prepare and execute the insertion query if no overlap is found
        $query = "INSERT INTO deluxesupersingles (roomNo, Name, Telephone, CheckIn, CheckOut, Status, Remark) VALUES (?, ?, ?, ?, ?, 'Confirmed', ?)";
        $stmt = mysqli_prepare($dbc, $query);

mysqli_stmt_bind_param($stmt, 'isssss', $roomNo, $name, $contact, $checkin, $checkout, $remark);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'Booking successfully added.';
            } else {
                $error = 'Database insertion failed: ' . mysqli_error($dbc);
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #bookingForm {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            background-color: #6c757d;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .error {
            color: #dc3545;
        }
        .success {
            color: #28a745;
        }
    </style>
</head>
<body>

    <div id="bookingForm">
        <h1>Add Booking</h1>

        <!-- Display success or error messages -->
        <?php if (isset($success)) { echo "<p class='message success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='message error'>$error</p>"; } ?>

        <!-- Booking Form -->
        <form method="POST" action="BookingDeluxeSuperSingle.php">
            <label for="roomNo">Room Number:</label>
            <input type="number" id="roomNo" name="roomNo" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" required>

            <label for="checkin">Check In:</label>
            <input type="date" id="checkin" name="checkin" required>

            <label for="checkout">Check Out:</label>
            <input type="date" id="checkout" name="checkout" required>

            <label for="remark">Remark:</label>
            <textarea id="remark" name="remark" rows="3"></textarea>

            <button type="submit">Confirm Booking</button>
        </form>

        <!-- Back Button -->
        <button class="back-button" onclick="location.href='Deluxe_Super_Single.php'">Back</button>
    </div>

</body>
</html>
