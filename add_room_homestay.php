<?php
require '_db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_id = intval($_POST['property_id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = intval($_POST['capacity']);
    $price = floatval($_POST['price']);
    $available = isset($_POST['available']) ? 1 : 0;

    // Handle multiple image upload
    $imagePaths = [];
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";

        foreach ($_FILES['images']['name'] as $key => $fileName) {
            $tempName = $_FILES['images']['tmp_name'][$key];
            $safeName = time() . '_' . basename($fileName); // unique name
            $destination = $targetDir . $safeName;

            if (move_uploaded_file($tempName, $destination)) {
                $imagePaths[] = $destination;
            }
        }
    }

    // Store as comma-separated paths
    $imagePath = implode(',', $imagePaths);

    // Insert room data
    $stmt = $dbc->prepare("INSERT INTO rooms (property_id, name, description, capacity, price, image, available) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issidsi", $property_id, $name, $description, $capacity, $price, $imagePath, $available);
    if ($stmt->execute()) {
        echo "<script>alert('Room added successfully.'); window.location.href='view_homestays.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch properties for dropdown
$properties = $dbc->query("SELECT id, name FROM properties");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fc;
            padding: 30px;
        }
        .form-container {
            max-width: 600px;
            background: #fff;
            padding: 25px 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], input[type="file"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="checkbox"] {
            margin-right: 5px;
        }
        button {
            margin-top: 25px;
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Room</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="property_id">Select Homestay</label>
        <select name="property_id" id="property_id" required>
            <option value="">-- Choose Property --</option>
            <?php while ($row = $properties->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="name">Room Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4" required></textarea>

        <label for="capacity">Capacity</label>
        <input type="number" name="capacity" id="capacity" min="1" required>

        <label for="price">Price (RM)</label>
        <input type="number" step="0.01" name="price" id="price" required>

        <label for="images">Upload Images</label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple required>

        <label>
            <input type="checkbox" name="available" checked>
            Available
        </label>

        <button type="submit">Add Room</button>
    </form>
    <a href="view_homestays.php" class="back-btn">&larr; Back to View Current Homestays</a>
</div>

</body>
</html>
