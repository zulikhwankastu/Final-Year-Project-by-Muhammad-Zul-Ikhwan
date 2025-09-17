<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../_db.php'; // Adjust if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $guests = intval($_POST['guests']);
    $totalPrice = floatval($_POST['total_price']);
    $specialRequest = isset($_POST['special_request']) ? trim($_POST['special_request']) : '';

    // Fetch room details
    $stmt = $dbc->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $stmt->close();

    if (!$room) {
        echo "<p>Room not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid request.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #eef2f3; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        .summary { margin-bottom: 20px; }
        .summary p { margin: 5px 0; }
        .btn { background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #218838; }
        select, input[type="text"] { width: 100%; padding: 8px; margin: 10px 0; }
        .italic { font-style: italic; color: #555; }
    </style>

    <script>
        function handlePaymentRedirect(form) {
            const method = form.payment_method.value;
            let actionUrl = "";

            switch (method) {
                case "Online Banking":
                    actionUrl = "bank_transfer.php";
                    break;
                case "DuitNow":
                    actionUrl = "pay_duitnow.php";
                    break;
                default:
                    alert("Please select a valid payment method.");
                    return false;
            }

            form.action = actionUrl;
            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Confirm Payment</h2>

    <div class="summary">
        <p><strong>Room:</strong> <?= htmlspecialchars($room['name']) ?></p>
        <p><strong>Check-in:</strong> <?= $checkIn ?></p>
        <p><strong>Check-out:</strong> <?= $checkOut ?></p>
        <p><strong>Guests:</strong> <?= $guests ?></p>
        <p><strong>Total Price:</strong> RM<?= number_format($totalPrice, 2) ?></p>
        <?php if (!empty($specialRequest)): ?>
            <p><strong>Special Request:</strong> <span class="italic"><?= htmlspecialchars($specialRequest) ?></span></p>
        <?php endif; ?>
    </div>

    <form method="POST" onsubmit="return handlePaymentRedirect(this);">
        <label for="payment_method">Select Payment Method:</label>
        <select name="payment_method" required>
            <option value="">-- Choose Payment Method --</option>
            <option value="Online Banking">Online Banking</option>
            <option value="DuitNow">DuitNow</option>
        </select>

        <input type="hidden" name="room_id" value="<?= htmlspecialchars($roomId) ?>">
        <input type="hidden" name="check_in" value="<?= htmlspecialchars($checkIn) ?>">
        <input type="hidden" name="check_out" value="<?= htmlspecialchars($checkOut) ?>">
        <input type="hidden" name="guests" value="<?= htmlspecialchars($guests) ?>">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars($totalPrice) ?>">
        <input type="hidden" name="special_request" value="<?= htmlspecialchars($specialRequest) ?>">

        <button type="submit" class="btn">💳 Continue to Payment</button>
    </form>
</div>

</body>
</html>
