<?php
session_start();
// You might want to check if manager is logged in here

require '../_db.php';

// Fetch bookings with user and room info where status is Approved or Checked Out
$query = "
    SELECT 
        b.id AS booking_id,
        u.full_name AS customer_name,
        u.email AS customer_email,
        u.phone AS customer_phone,
        r.name AS room_name,
        b.check_in,
        b.check_out,
        b.guests,
        b.payment_status
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN rooms r ON b.room_id = r.id
    WHERE b.payment_status IN ('Approved', 'Checked Out')
    ORDER BY b.check_in DESC
";

$result = $dbc->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Customers Who Stayed</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>

<h2>Customers Who Stayed</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Guests</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['booking_id']) ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['customer_email']) ?></td>
                    <td><?= htmlspecialchars($row['customer_phone']) ?></td>
                    <td><?= htmlspecialchars($row['room_name']) ?></td>
                    <td><?= htmlspecialchars($row['check_in']) ?></td>
                    <td><?= htmlspecialchars($row['check_out']) ?></td>
                    <td><?= htmlspecialchars($row['guests']) ?></td>
                    <td><?= htmlspecialchars($row['payment_status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No customers found who stayed yet.</p>
<?php endif; ?>

</body>
</html>
