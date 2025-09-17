<?php
require_once '_db.php'; // your DB connection file

// Query to aggregate sales by room and payment method for approved bookings
$sql = "
    SELECT room_id, 
           SUM(guests) AS total_guests, 
           SUM(total_price) AS total_price, 
           payment_method
    FROM bookings
    WHERE payment_status = 'Approved' AND status = 'Approved'
    GROUP BY room_id, payment_method
    ORDER BY room_id
";
$result = $dbc->query($sql);

// Calculate grand total sales
$grandTotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Sales Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0fff0;
            padding: 30px;
            color: #155724;
        }
        h1 {
            color: #2e7d32;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(46,125,50,0.2);
            font-size: 1rem;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #a5d6a7;
            text-align: left;
        }
        th {
            background-color: #81c784;
            color: #1b5e20;
        }
        tr:nth-child(even) {
            background-color: #e8f5e9;
        }
        tfoot td {
            font-weight: bold;
            background-color: #a5d6a7;
            color: #1b5e20;
            text-align: right;
        }
        .back-button {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            background-color: #4caf50;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <h1>Sales Report - Approved Bookings</h1>

    <?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Room ID</th>
                <th>Total Guests</th>
                <th>Total Price (RM)</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): 
                $grandTotal += floatval($row['total_price']);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['room_id']) ?></td>
                <td><?= htmlspecialchars($row['total_guests']) ?></td>
                <td><?= number_format($row['total_price'], 2) ?></td>
                <td><?= htmlspecialchars($row['payment_method']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;">Total Sales:</td>
                <td colspan="2">RM <?= number_format($grandTotal, 2) ?></td>
            </tr>
        </tfoot>
    </table>
    <?php else: ?>
        <p>No approved sales found.</p>
    <?php endif; ?>

    <a href="manager_dashboard.php" class="back-button">← Back to Dashboard</a>
</body>
</html>
