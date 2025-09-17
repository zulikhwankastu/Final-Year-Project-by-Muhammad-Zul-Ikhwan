<?php
require '_db.php';

// Check if user_id is valid
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die("Invalid user ID.");
}

$userId = intval($_GET['user_id']);

// Handle remark update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remark'])) {
    $remark = $_POST['remark'];
    $stmt = $dbc->prepare("UPDATE users SET remark = ? WHERE id = ?");
    $stmt->bind_param("si", $remark, $userId);
    $stmt->execute();
    $stmt->close();

    // Refresh to show updated remark without form
    header("Location: view_user_details.php?user_id=" . $userId);
    exit;
}

// Fetch user details
$stmt = $dbc->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Details - <?= htmlspecialchars($user['full_name']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 30px 15px;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 25px;
            font-weight: 700;
            color: #222;
            text-align: center;
        }
        .profile-pic {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-pic img {
            border-radius: 50%;
            max-width: 150px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .details p {
            font-size: 17px;
            margin: 10px 0;
        }
        .details strong {
            color: #555;
            width: 130px;
            display: inline-block;
        }
        .remark {
            background: #eef6ff;
            border-left: 5px solid #007bff;
            padding: 8px 12px;
            margin: 12px 0 20px 130px;
            border-radius: 4px;
            color: #004085;
            font-style: italic;
        }
        form textarea {
            width: 100%;
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            resize: vertical;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }
        form textarea:focus {
            border-color: #007bff;
            outline: none;
        }
        form button {
            margin-top: 15px;
            background-color: #007bff;
            border: none;
            padding: 12px 30px;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin-left: auto;
        }
        form button:hover {
            background-color: #0056b3;
        }
        .edit-remark-btn {
            background-color: #6c757d;
            border: none;
            padding: 8px 16px;
            color: white;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
            vertical-align: middle;
            transition: background-color 0.3s ease;
        }
        .edit-remark-btn:hover {
            background-color: #5a6268;
        }
        .back-link {
            margin-top: 35px;
            display: block;
            text-align: center;
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>User Details</h2>

    <div class="profile-pic">
        <?php if (!empty($user['profile_picture'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" />
        <?php else: ?>
            <img src="https://via.placeholder.com/150?text=No+Image" alt="No Profile Picture" />
        <?php endif; ?>
    </div>

    <div class="details">
        <p><strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>

        <?php if (!empty($user['remark'])): ?>
            <p><strong>Remark:</strong></p>
            <div class="remark"><?= nl2br(htmlspecialchars($user['remark'])) ?></div>
            <button class="edit-remark-btn" onclick="showEditRemark()">Edit Remark</button>
        <?php else: ?>
            <p><strong>Remark:</strong> None</p>
            <button class="edit-remark-btn" onclick="showEditRemark()">Add Remark</button>
        <?php endif; ?>
    </div>

    <div id="remarkFormContainer" style="display:none; margin-top: 20px;">
        <form method="POST">
            <textarea name="remark" rows="5" placeholder="Enter remarks about this user..."><?= htmlspecialchars($user['remark'] ?? '') ?></textarea>
            <button type="submit">Save Remark</button>
            <button type="button" onclick="hideEditRemark()" style="margin-left:10px; background:#dc3545;">Cancel</button>
        </form>
    </div>

    

    <a href="customer_list.php" class="back-link">&larr; Back to User List</a>
</div>

<script>
    function showEditRemark() {
        document.getElementById('remarkFormContainer').style.display = 'block';
    }
    function hideEditRemark() {
        document.getElementById('remarkFormContainer').style.display = 'none';
    }
</script>
</body>
</html>
