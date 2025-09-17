<?php
require '_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = $_POST['name'];
    $address    = $_POST['address'];
    $city       = $_POST['city'];
    $state      = $_POST['state'];
    $zip_code   = $_POST['zip_code'];
    $description= $_POST['description'];
    $amenities  = $_POST['amenities'];

    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = basename($_FILES['image']['name']);
        $targetPath = 'images/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $stmt = $dbc->prepare("INSERT INTO properties (name, address, city, state, zip_code, description, amenities, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $name, $address, $city, $state, $zip_code, $description, $amenities);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Homestay added successfully!'); window.location.href='manage_properties.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Homestay</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 700px;
            margin: auto;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        form button {
            margin-top: 25px;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function confirmSubmission(event) {
            const confirmed = confirm("Are you sure you want to add this homestay?");
            if (!confirmed) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add New Homestay</h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="confirmSubmission(event)">
            <label>Homestay Name:</label>
            <input type="text" name="name" required>

            <label>Address:</label>
            <textarea name="address" rows="2" required></textarea>

            <label>City:</label>
            <input type="text" name="city" required>

            <label>State:</label>
            <input type="text" name="state" required>

            <label>Zip Code:</label>
            <input type="text" name="zip_code" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Amenities (comma-separated):</label>
            <input type="text" name="amenities">

            <label>Upload Image:</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">Add Homestay</button>
        </form>
    </div>
</body>
</html>
