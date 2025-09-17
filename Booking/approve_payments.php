<?php
session_start();
require '../_db.php';

// Handle Approve/Reject
// Handle Approve/Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $bookingId = intval($_POST['booking_id']);

    if ($_POST['action'] === 'approve') {
        $stmt = $dbc->prepare("UPDATE bookings SET payment_status = 'Approved', status = 'Approved' WHERE id = ?");
        $stmt->bind_param("i", $bookingId);
    } elseif ($_POST['action'] === 'reject' && isset($_POST['reason'])) {
        $reason = trim($_POST['reason']);
        $stmt = $dbc->prepare("UPDATE bookings SET payment_status = 'Rejected', status = 'Rejected', cancellation_reason = ? WHERE id = ?");
        $stmt->bind_param("si", $reason, $bookingId);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $stmt->close();
    }
}
// Get all pending bookings
$result = $dbc->query("SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.payment_status = 'Pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Payments</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        img { max-width: 100px; height: auto; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; color: white; cursor: pointer; }
        .approve { background-color: #28a745; }
        .reject { background-color: #dc3545; }
        .btn:hover { opacity: 0.9; }

        /* Modal */
        .modal {
            display: none; position: fixed; z-index: 1000;
            left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }
        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 20px;
            width: 90%; max-width: 400px;
            border-radius: 10px;
            text-align: center;
        }
        textarea { width: 100%; height: 80px; margin-top: 10px; padding: 8px; }
        .close-btn { margin-top: 10px; background: #6c757d; }
    </style>
</head>
<body>

<div class="container">

<div style="text-align: right; margin-bottom: 15px;">
    <a href="../manager_dashboard.php" class="btn" style="background-color: #007bff; text-decoration: none; padding: 8px 15px; border-radius: 5px; color: white;">&larr; Back to Dashboard</a>
</div>

    <h2>Pending Payments Approval</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Guests</th>
                <th>Total (RM)</th>
                <th>Receipt</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['room_name']) ?></td>
                    <td><?= $row['check_in'] ?></td>
                    <td><?= $row['check_out'] ?></td>
                    <td><?= $row['guests'] ?></td>
                    <td><?= number_format($row['total_price'], 2) ?></td>
                    <td>
                        <?php if (!empty($row['receipt_path'])): ?>
                            <a href="<?= htmlspecialchars($row['receipt_path']) ?>" target="_blank">
                                <img src="<?= htmlspecialchars($row['receipt_path']) ?>" alt="Receipt">
                            </a>
                        <?php else: ?>
                            No receipt
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                        </form>
                        <button class="btn reject" onclick="openRejectModal(<?= $row['id'] ?>)">Reject</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No pending payments to approve.</p>
    <?php endif; ?>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <h3>Reject Booking</h3>
        <form method="POST">
            <input type="hidden" name="booking_id" id="rejectBookingId">
            <input type="hidden" name="action" value="reject">
            <label for="reason">Reason for Rejection:</label>
            <textarea name="reason" id="reason" required></textarea>
            <br>
            <button type="submit" class="btn reject">Confirm Reject</button>
            <button type="button" onclick="closeRejectModal()" class="btn close-btn">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openRejectModal(bookingId) {
        document.getElementById('rejectBookingId').value = bookingId;
        document.getElementById('reason').value = '';
        document.getElementById('rejectModal').style.display = 'block';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    // Close when clicking outside modal
    window.onclick = function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
