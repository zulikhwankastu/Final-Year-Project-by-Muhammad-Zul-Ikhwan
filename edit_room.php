<?php
require '_db.php';

if (!isset($_GET['room_id']) || !isset($_GET['property_id'])) {
    die("Missing required parameters.");
}

$room_id = intval($_GET['room_id']);
$property_id = intval($_GET['property_id']);
$room = null;
$message = "";

// Fetch room details
$stmt = $dbc->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $room = $result->fetch_assoc();
} else {
    die("Room not found.");
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $capacity = intval($_POST['capacity']);
    $price = floatval($_POST['price']);
    $available = isset($_POST['available']) ? 1 : 0;
    $newImagePath = $room['image']; // default to current image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $newFileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            if (!empty($room['image']) && file_exists($room['image'])) {
                unlink($room['image']);
            }
            $newImagePath = $targetPath;
        } else {
            $message = "❌ Failed to upload image.";
        }
    }

    $stmt = $dbc->prepare("UPDATE rooms SET name = ?, description = ?, capacity = ?, price = ?, image = ?, available = ? WHERE id = ?");
    $stmt->bind_param("ssidsii", $name, $description, $capacity, $price, $newImagePath, $available, $room_id);

    if ($stmt->execute()) {
        header("Location: view_homestay_details.php?id=" . $property_id);
        exit();
    } else {
        $message = "❌ Failed to update room.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            margin: 0;
            padding: 0;
        }

        .container {
            background: white;
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
        }

        input[type="checkbox"] {
            margin-right: 8px;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            margin-top: 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            background: #6c757d;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
        }

        .back-link:hover {
            background: #5a6268;
        }

        img {
            display: block;
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-left: 5px solid #f5c6cb;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛏️ Edit Room Details</h2>

        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="name">Room Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($room['name']) ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($room['description']) ?></textarea>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" value="<?= $room['capacity'] ?>" required>

            <label for="price">Price (RM):</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= $room['price'] ?>" required>

            <label>Current Image:</label>
            <?php if (!empty($room['image'])): ?>
                <img src="<?= htmlspecialchars($room['image']) ?>" alt="Room Image">
            <?php else: ?>
                <p><em>No image uploaded.</em></p>
            <?php endif; ?>

            <label for="image">Upload New Image:</label>
            <input type="file" name="image" id="image" accept="image/*">

            <label>
                <input type="checkbox" name="available" <?= $room['available'] ? 'checked' : '' ?>> Available
            </label>

            <button type="submit" class="btn">💾 Update Room</button>
            <a href="view_homestay_details.php?id=<?= $property_id ?>" class="back-link">🔙 Back to Property</a>
        </form>
    </div>
</body>
</html>
