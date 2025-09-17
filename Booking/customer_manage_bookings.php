<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../_db.php';

$userId = $_SESSION['user_id'];

// Handle booking cancellation: update status instead of payment_status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $cancelId = intval($_POST['cancel_booking_id']);
    $stmt = $dbc->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cancelId, $userId);
    $stmt->execute();
    $stmt->close();
}

// Get bookings for current user
$query = "
    SELECT b.*, r.name AS room_name
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.user_id = ?
    ORDER BY b.check_in DESC
";
$stmt = $dbc->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        .booking-card {
            background: white;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            position: relative;
        }
        .details { display: none; margin-top: 10px; }
        .btn-toggle, .btn-cancel, .btn-close {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        .btn-toggle {
            background: #007bff;
            color: white;
        }
        .btn-toggle:hover { background: #0056b3; }
        .btn-cancel {
            background: #dc3545;
            color: white;
        }
        .btn-cancel:hover { background: #c82333; }
        .btn-close {
            background: #6c757d;
            color: white;
        }
        .btn-close:hover { background: #5a6268; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
        }
        .modal-content {
            background: white;
            padding: 20px;
            margin: 10% auto;
            width: 90%;
            max-width: 400px;
            border-radius: 10px;
            text-align: center;
        }
        .btn-dashboard {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-dashboard:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<a href="booking_dashboard.php" class="btn-dashboard">🏠 Back to Dashboard</a>

<h2>My Bookings</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="booking-card">
        <strong>Room:</strong> <?= htmlspecialchars($row['room_name']) ?><br>
        <strong>Check-In:</strong> <?= htmlspecialchars($row['check_in']) ?><br>
        <strong>Check-Out:</strong> <?= htmlspecialchars($row['check_out']) ?><br>

        <button class="btn-toggle" onclick="toggleDetails(this)">View Details</button>

        <div class="details">
            <strong>Guests:</strong> <?= htmlspecialchars($row['guests']) ?><br>
            <strong>Total Price:</strong> RM <?= htmlspecialchars(number_format($row['total_price'], 2)) ?><br>
            <strong>Payment Method:</strong> <?= htmlspecialchars($row['payment_method']) ?><br>
            <strong>Status:</strong> <?= htmlspecialchars($row['status']) ?><br>
            <strong>Special Request:</strong> <?= htmlspecialchars($row['special_request']) ?><br>

            <?php if ($row['status'] === 'Rejected' && !empty($row['cancellation_reason'])): ?>
                <strong>Rejection Reason:</strong> <?= htmlspecialchars($row['cancellation_reason']) ?><br>
            <?php endif; ?>

            <?php if (!empty($row['receipt_path'])): ?>
                <strong>Receipt:</strong> <a href="<?= htmlspecialchars($row['receipt_path']) ?>" target="_blank">View Receipt</a><br>
            <?php endif; ?>

            <?php if (!in_array($row['status'], ['Cancelled', 'Rejected'])): ?>
                <button class="btn-cancel" onclick="openCancelModal(<?= $row['id'] ?>)">Cancel Booking</button>
            <?php endif; ?>
        </div>
    </div>
<?php endwhile; ?>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <h3>Cancel Booking</h3>
        <p>Are you sure you want to cancel this booking?</p>
        <form method="POST" id="cancelForm">
            <input type="hidden" name="cancel_booking_id" id="cancel_booking_id">
            <button type="submit" class="btn-cancel">Yes, Cancel</button>
            <button type="button" onclick="closeCancelModal()" class="btn-close">No</button>
        </form>
    </div>
</div>

<script>
    function toggleDetails(button) {
        const details = button.nextElementSibling;
        if (details.style.display === 'block') {
            details.style.display = 'none';
            button.innerText = 'View Details';
        } else {
            details.style.display = 'block';
            button.innerText = 'Hide Details';
        }
    }

    function openCancelModal(bookingId) {
        document.getElementById('cancel_booking_id').value = bookingId;
        document.getElementById('cancelModal').style.display = 'block';
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('cancelModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
