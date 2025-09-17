<?php
require '_db.php';

// Check if property_id is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid property ID.");
}

$propertyId = intval($_GET['id']);

// Fetch property details
$stmt = $dbc->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $propertyId);
$stmt->execute();
$propertyResult = $stmt->get_result();
$property = $propertyResult->fetch_assoc();
$stmt->close();

if (!$property) {
    die("Property not found.");
}

// Fetch rooms for this property (updated query without checkIn and checkOut)
$stmt = $dbc->prepare("SELECT id, name, description, price, capacity, image, available FROM rooms WHERE property_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $propertyId);
$stmt->execute();
$roomsResult = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($property['name']) ?> - Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 30px 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        h1 {
            margin-bottom: 15px;
            color: #222;
        }
        .property-info p {
            font-size: 16px;
            margin: 8px 0;
        }
        .rooms {
            margin-top: 35px;
        }
        .room-card {
            display: flex;
            background: #fefefe;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .room-image {
            width: 200px;
            object-fit: cover;
        }
        .room-details {
            padding: 15px 20px;
            flex-grow: 1;
        }
        .room-details h4 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .room-details p {
            margin: 5px 0;
            font-size: 15px;
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
        .action-buttons {
            position: absolute;
            top: 25px;
            right: 30px;
            display: flex;
            gap: 10px;
        }
        .btn {
            background-color: #007bff;
            border: none;
            padding: 10px 18px;
            color: white;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="action-buttons">
        <a href="add_room_homestay.php?property_id=<?= $propertyId ?>" class="btn">Add Room</a>
        <a href="update_homestay.php?id=<?= $propertyId ?>" class="btn">Update Homestay</a>
    </div>

    <h1><?= htmlspecialchars($property['name']) ?></h1>

    <div class="property-info">
        <p><strong>Address:</strong> <?= htmlspecialchars($property['address']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($property['city']) ?></p>
        <p><strong>State:</strong> <?= htmlspecialchars($property['state']) ?></p>
        <p><strong>Zip Code:</strong> <?= htmlspecialchars($property['zip_code']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($property['description'])) ?></p>
        <?php if (!empty($property['amenities'])): ?>
            <p><strong>Amenities:</strong> <?= nl2br(htmlspecialchars($property['amenities'])) ?></p>
        <?php endif; ?>
        <p><strong>Created At:</strong> <?= htmlspecialchars($property['created_at']) ?></p>
    </div>

    <h2 class="rooms-title">Rooms</h2>
    <div class="rooms">
        <?php if ($roomsResult->num_rows === 0): ?>
            <p>No rooms available for this property.</p>
        <?php else: ?>
           <?php while ($room = $roomsResult->fetch_assoc()): ?>
            <?php
              $imagePaths = explode(',', $room['image']);
              $firstImage = trim($imagePaths[0]);
             ?>
             <div class="room-card">
               <img class="room-image" src="<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($room['name']) ?>" />
               <div class="room-details">
                   <h4><?= htmlspecialchars($room['name']) ?></h4>
                   <p><?= htmlspecialchars($room['description']) ?></p>
                   <p><strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']) ?> guest(s)</p>
                   <p><strong>Price:</strong> RM<?= number_format($room['price'], 2) ?></p>
                   <p><strong>Available:</strong> <?= $room['available'] ? 'Yes' : 'No' ?></p>

                 <div class="room-actions" style="margin-top: 10px;">
                    <a href="edit_room.php?room_id=<?= $room['id'] ?>&property_id=<?= $propertyId ?>" class="btn" style="background-color: #28a745;">Edit</a>
                   <a href="delete_room_homestay.php?room_id=<?= $room['id'] ?>&property_id=<?= $propertyId ?>" 
                      class="btn" 
                      style="background-color: #dc3545;" 
                      onclick="return confirm('Are you sure you want to delete this room?');">
                        Delete
                   </a>
 </div>
                </div>
             </div>
            <?php endwhile; ?>
             <?php endif; ?>
        </div>

    <a href="view_homestays.php" class="back-link">&larr; Back to Homestays List</a>
</div>
</body>
</html>
