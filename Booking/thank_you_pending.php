<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Pending</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 50px;
            text-align: center;
        }
        .box {
            background: white;
            padding: 40px;
            margin: auto;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .box h2 {
            color: #28a745;
        }
        .box p {
            font-size: 18px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>🎉 Thank You!</h2>
    <p>Your booking is <strong>pending approval</strong>.</p>
    <p>You will be notified once your payment is verified by our manager.</p>
    <a href="booking_dashboard.php" class="btn">🏠 Go to Dashboard</a>
</div>

</body>
</html>
