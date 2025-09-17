<?php
require '_db.php';

// Check if the required parameters are present
if (!isset($_GET['room_id']) || !isset($_GET['property_id'])) {
    die("Missing required parameters.");
}

$room_id = intval($_GET['room_id']);
$property_id = intval($_GET['property_id']);

// Optional: Fetch the image path first so we can delete the image file
$stmt = $dbc->prepare("SELECT image FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$imagePath = '';

if ($result->num_rows === 1) {
    $room = $result->fetch_assoc();
    $imagePath = $room['image'];
}
$stmt->close();

// Delete the room from the database
$stmt = $dbc->prepare("DELETE FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
if ($stmt->execute()) {
    // Optionally delete image file from server
    if (!empty($imagePath) && file_exists($imagePath)) {
        unlink($imagePath); // delete image
    }

    // Redirect back to the property detail page
    header("Location: view_homestay_details.php?id=" . $property_id);
    exit();
} else {
    echo "<script>alert('Failed to delete room.'); window.history.back();</script>";
}
$stmt->close();
?>
