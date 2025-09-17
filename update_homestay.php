<?php
require '_db.php';

// Check if id is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid property ID.");
}

$propertyId = intval($_GET['id']);
$error = '';
$success = '';

// Fetch current data for form prefill BEFORE processing POST
$stmt = $dbc->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $propertyId);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();
$stmt->close();

if (!$property) {
    die("Property not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $amenities = trim($_POST['amenities'] ?? '');

    // Basic validation
    if ($name === '' || $address === '' || $city === '' || $state === '' || $zip_code === '' || $description === '') {
        $error = "Please fill in all required fields.";
    } else {
        // Handle image upload if a new file was provided
        $imageFileName = $property['image']; // current image filename
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed image extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                // Sanitize file name and generate unique name
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = './uploads/'; // Make sure this folder exists and is writable
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imageFileName = $newFileName; // update image filename to save
                } else {
                    $error = "There was an error moving the uploaded file.";
                }
            } else {
                $error = "Upload failed. Allowed file types: " . implode(", ", $allowedExtensions);
            }
        }

        if (!$error) {
            // Update the database, including the image filename
            $stmt = $dbc->prepare("UPDATE properties SET name = ?, address = ?, city = ?, state = ?, zip_code = ?, description = ?, amenities = ?, image = ? WHERE id = ?");
            $stmt->bind_param("ssssssssi", $name, $address, $city, $state, $zip_code, $description, $amenities, $imageFileName, $propertyId);

            if ($stmt->execute()) {
                $success = "Homestay updated successfully.";
                // Refresh $property to reflect updated data
                $property['name'] = $name;
                $property['address'] = $address;
                $property['city'] = $city;
                $property['state'] = $state;
                $property['zip_code'] = $zip_code;
                $property['description'] = $description;
                $property['amenities'] = $amenities;
                $property['image'] = $imageFileName;
            } else {
                $error = "Database update failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Homestay - <?= htmlspecialchars($property['name']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 30px;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 20px;
            color: #007bff;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 15px;
            resize: vertical;
        }
        textarea {
            min-height: 100px;
        }
        .btn {
            margin-top: 25px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 22px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 6px;
        }
        .error {
            background-color: #ffdddd;
            border: 1px solid #dd4b39;
            color: #a12a19;
        }
        .success {
            background-color: #ddffdd;
            border: 1px solid #2ca02c;
            color: #206020;
        }
        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        img.current-image {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Update Homestay: <?= htmlspecialchars($property['name']) ?></h1>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Name *</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($property['name']) ?>" />

        <label for="address">Address *</label>
        <textarea id="address" name="address" required><?= htmlspecialchars($property['address']) ?></textarea>

        <label for="city">City *</label>
        <input type="text" id="city" name="city" required value="<?= htmlspecialchars($property['city']) ?>" />

        <label for="state">State *</label>
        <input type="text" id="state" name="state" required value="<?= htmlspecialchars($property['state']) ?>" />

        <label for="zip_code">Zip Code *</label>
        <input type="text" id="zip_code" name="zip_code" required value="<?= htmlspecialchars($property['zip_code']) ?>" />

        <label for="description">Description *</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($property['description']) ?></textarea>

        <label for="amenities">Amenities</label>
        <textarea id="amenities" name="amenities"><?= htmlspecialchars($property['amenities']) ?></textarea>

        <label for="image">Upload Image</label>
        <input type="file" id="image" name="image" accept="image/*" />
        <?php if ($property['image']): ?>
            <div>
                <p>Current Image:</p>
                <img src="uploads/<?= htmlspecialchars($property['image']) ?>" alt="Current Image" class="current-image" />
            </div>
        <?php endif; ?>

        <button type="submit" class="btn">Update Homestay</button>
    </form>

    <a href="view_homestays.php" class="back-link">&larr; Back to Homestays List</a>
</div>
</body>
</html>
