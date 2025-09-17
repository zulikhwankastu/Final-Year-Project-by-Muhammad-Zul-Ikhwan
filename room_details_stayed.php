<?php
require '_db.php';

if (!isset($_GET['roomId'])) {
    die('Room ID is required');
}

$roomId = intval($_GET['roomId']);

$stmt = $dbc->prepare("
    SELECT b.*, u.full_name AS user_name, u.email, u.phone, u.profile_picture
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.id
    WHERE b.room_id = ?
    ORDER BY b.check_in ASC
");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Room Bookings Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4ed;
            margin: 0; 
            padding: 20px;
            color: #2d2d2a;
        }
        h1 {
            text-align: center;
            color: #4caf50;
        }
        table {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: white;
            border-radius: 6px;
            overflow: hidden;
        }
        thead {
            background-color: #4caf50;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f1f8e9;
        }
        a {
            color: #4caf50;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        p {
            max-width: 1200px;
            margin: 20px auto;
            text-align: center;
        }
        /* Responsive Table */
        @media (max-width: 900px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                display: none;
            }
            tr {
                margin-bottom: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                background-color: white;
                padding: 15px;
            }
            td {
                padding-left: 50%;
                position: relative;
                border: none;
                border-bottom: 1px solid #eee;
                text-align: left;
            }
            td:last-child {
                border-bottom: 0;
            }
            td::before {
                position: absolute;
                top: 12px;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #4caf50;
                content: attr(data-label);
            }
        }
    </style>
</head>
<body>
    <h1>Bookings for Room ID <?= htmlspecialchars($roomId) ?></h1>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Guests</th>
                <th>Total Price (RM)</th>
                <th>Payment Status</th>
                <th>Receipt</th>
                <th>Special Request</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Customer Name"><?= htmlspecialchars($booking['user_name']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($booking['email']) ?></td>
                        <td data-label="Phone"><?= htmlspecialchars($booking['phone']) ?></td>
                        <td data-label="Check-In"><?= htmlspecialchars($booking['check_in']) ?></td>
                        <td data-label="Check-Out"><?= htmlspecialchars($booking['check_out']) ?></td>
                        <td data-label="Guests"><?= intval($booking['guests']) ?></td>
                        <td data-label="Total Price (RM)"><?= number_format($booking['total_price'], 2) ?></td>
                        <td data-label="Payment Status"><?= htmlspecialchars($booking['payment_status']) ?></td>
                        <td data-label="Receipt">
                            <?php if (!empty($booking['receipt_path'])): ?>
                                <a href="<?= htmlspecialchars($booking['receipt_path']) ?>" target="_blank">View Receipt</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td data-label="Special Request"><?= htmlspecialchars($booking['special_request'] ?? '') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align:center;">No bookings found for this room.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="manage_rooms.php">Back to Manage Rooms</a></p>
</body>
</html>
