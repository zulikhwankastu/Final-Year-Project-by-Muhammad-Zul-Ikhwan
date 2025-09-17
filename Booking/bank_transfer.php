<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../_db.php'; // Make sure this connects to your database

// Handle form submission
$submissionResult = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['receipt'])) {
    $userId = $_SESSION['user_id'];
    $roomId = $_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $guests = intval($_POST['guests']);
    $totalPrice = floatval($_POST['total_price']);
    $specialRequest = isset($_POST['special_request']) ? trim($_POST['special_request']) : null;

    if ($_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../receipts/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $receiptName = uniqid('receipt_') . '_' . basename($_FILES['receipt']['name']);
        $receiptPath = $uploadDir . $receiptName;

        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $receiptPath)) {
            $stmt = $dbc->prepare("
                INSERT INTO bookings (
                    user_id, room_id, check_in, check_out, guests, total_price,
                    payment_method, payment_status, receipt_path, special_request
                )
                VALUES (?, ?, ?, ?, ?, ?, 'Bank Transfer', 'Pending', ?, ?)
            ");
            $stmt->bind_param("iissidss", $userId, $roomId, $checkIn, $checkOut, $guests, $totalPrice, $receiptPath, $specialRequest);
            $stmt->execute();
            $stmt->close();

            header("Location:thank_you_pending.php");
            exit;
        } else {
            $submissionResult = "<p style='color:red;'>❌ Failed to upload receipt. Please try again.</p>";
        }
    } else {
        $submissionResult = "<p style='color:red;'>❌ No receipt uploaded or file error.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bank Transfer Payment</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .bank-info {
            background: #eef5ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        input[type="file"], .btn, textarea {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover { background: #218838; }
        .back-btn {
            background: #007bff;
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bank Transfer Payment</h2>

    <div class="bank-info">
        <p><strong>Bank Name:</strong> Cimbbank</p>
        <p><strong>Account Number:</strong> 7076072204</p>
        <p><strong>Account Holder:</strong> HomestayHub Sdn Bhd</p>
        <p>Please transfer the amount to the account above and upload your receipt below.</p>
    </div>

    <?= $submissionResult ?>

    <?php if ($submissionResult === ""): ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Upload Receipt (Screenshot or PDF):</label>
        <input type="file" name="receipt" accept="image/*,.pdf" required>

        <label for="special_request">Special Request (optional):</label>
        <textarea name="special_request" rows="4" placeholder="Any special instructions or requests..."><?= htmlspecialchars($_POST['special_request'] ?? '') ?></textarea>

        <!-- Hidden booking info -->
        <input type="hidden" name="room_id" value="<?= htmlspecialchars($_POST['room_id'] ?? '') ?>">
        <input type="hidden" name="check_in" value="<?= htmlspecialchars($_POST['check_in'] ?? '') ?>">
        <input type="hidden" name="check_out" value="<?= htmlspecialchars($_POST['check_out'] ?? '') ?>">
        <input type="hidden" name="guests" value="<?= htmlspecialchars($_POST['guests'] ?? '') ?>">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars($_POST['total_price'] ?? '') ?>">

        <button type="submit" class="btn">📤 Submit Receipt & Confirm Booking</button>
       
    </form>
    <?php endif; ?>
</div>

</body>
</html>
