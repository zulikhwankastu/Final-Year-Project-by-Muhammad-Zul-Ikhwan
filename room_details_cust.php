<?php
require '_db.php';

if (!isset($_GET['room_id']) || !filter_var($_GET['room_id'], FILTER_VALIDATE_INT)) {
    echo "Invalid room ID.";
    exit;
}

$room_id = $_GET['room_id'];

// Fetch room details
$stmt = $dbc->prepare("SELECT rooms.*, properties.name AS property_name 
                       FROM rooms 
                       JOIN properties ON rooms.property_id = properties.id 
                       WHERE rooms.id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Room not found.";
    exit;
}
$room = $result->fetch_assoc();
$stmt->close();

// Fetch reviews with conditions
$review_stmt = $dbc->prepare("
    SELECT name, rating, comment, created_at, manager_response 
    FROM reviews 
    WHERE room_id = ? 
      AND status = 'visible' 
      AND flagged = 0 
     
    ORDER BY created_at DESC
");
$review_stmt->bind_param("i", $room_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($room['name']) ?> - Room Details</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.06);
        }

        h1 {
            text-align: center;
            font-size: 2.2em;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .gallery {
            flex: 1 1 35%;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }

        .gallery img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #ddd;
            transition: 0.3s;
            cursor: pointer;
        }

        .gallery img:hover {
            transform: scale(1.03);
            border-color: #007bff;
        }

        .room-info {
            flex: 1 1 60%;
            background: #f9f9f9;
            padding: 25px;
            border-radius: 10px;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
        }

        .room-info p {
            font-size: 1.1em;
            margin-bottom: 15px;
            padding-left: 8px;
            border-left: 4px solid #007bff;
        }

        .room-info strong {
            display: inline-block;
            width: 140px;
            font-weight: 600;
        }

        .back-btn {
            display: inline-block;
            margin-top: 40px;
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        /* Review Section */
        .reviews {
            margin-top: 50px;
        }

        .reviews h2 {
            font-size: 1.6em;
            margin-bottom: 20px;
            color: #444;
        }

        .review {
            background: #f4f4f4;
            border-left: 5px solid #007bff;
            margin-bottom: 15px;
            padding: 15px 20px;
            border-radius: 6px;
        }

        .review strong {
            display: block;
            margin-bottom: 5px;
            color: #007bff;
        }

        .review small {
            color: #777;
        }

        form {
            margin-top: 30px;
            background: #eef1f5;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin: 12px 0 5px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        button {
            margin-top: 15px;
            padding: 10px 18px;
            background: #28a745;
            color: #fff;
            border: none;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        /* Lightbox */
        #lightbox {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.85);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        #lightbox img {
            max-width: 90%;
            max-height: 90%;
            border: 5px solid white;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .gallery, .room-info {
                flex: 1 1 100%;
            }
        }
        .book-now-btn {
    display: inline-block;
    float: right;
    background-color: #ffc107;
    color: #000;
    padding: 12px 20px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 6px;
    border: 2px solid #e0a800;
    margin-top: 20px;
    transition: background 0.3s, transform 0.2s;
}

.book-now-btn:hover {
    background-color: #e0a800;
    transform: translateY(-2px);
}
.booking-card {
    flex: 0 0 250px;
    background: #e6f4ea; /* light green background */
    padding: 25px 20px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(76, 175, 80, 0.4); /* soft green shadow */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.booking-card h3 {
    margin-bottom: 20px;
    color: #2e7d32; /* darker green */
    font-weight: 700;
    font-size: 1.4rem;
}

.book-now-btn {
    background-color: #4caf50; /* medium green */
    color: #fff;
    padding: 14px 30px;
    font-weight: 700;
    text-decoration: none;
    border-radius: 8px;
    border: 2px solid #388e3c; /* darker green border */
    transition: background 0.3s ease, transform 0.2s ease;
    text-align: center;
    width: 100%;
}

.book-now-btn:hover {
    background-color: #388e3c; /* darker green on hover */
    transform: translateY(-3px);
}

/* Adjust layout for smaller screens */
@media (max-width: 991px) {
    .content {
        flex-direction: column;
    }

    .booking-card {
        flex: 1 1 100%;
        margin-top: 30px;
        width: 100%;
    }
}


    </style>
</head>
<body>

<div class="container">
    <h1><?= htmlspecialchars($room['name']) ?> – Room Details</h1>

    <?php
    $images = explode(',', $room['image']);
    $valid_images = array_filter(array_map('trim', $images));
    if (empty($valid_images)) {
        $valid_images = ['Images/default-room.jpg'];
    }
    ?>

    <div class="content">
        <div class="gallery">
            <?php foreach ($valid_images as $img): ?>
                <img src="<?= htmlspecialchars($img) ?>" alt="Room Image">
            <?php endforeach; ?>
        </div>

        <div class="room-info">
    <p><strong>🏠 Homestay:</strong> <?= htmlspecialchars($room['property_name']) ?></p>
    <p><strong>💰 Price:</strong> RM<?= number_format($room['price'], 2) ?> / night</p>
    <p><strong>📅 Status:</strong>
        <?= $room['available'] ? '<span style="color:green;">Available</span>' : '<span style="color:red;">Unavailable</span>' ?>
    </p>
    <p><strong>📝 Description:</strong> <?= htmlspecialchars($room['description'] ?? 'No description provided.') ?></p>

    
</div>

 <div class="booking-card">
        <h3>Ready to Book?</h3>
        <a href="login.php" class="book-now-btn">Book Now</a>
    </div>

    </div>

    <!-- Reviews Section -->
    <div class="reviews">
        <h2>🗣️ Customer Reviews</h2>
       <?php if ($reviews->num_rows > 0): ?>
    <?php while ($rev = $reviews->fetch_assoc()): ?>
        <div class="review">
            <strong><?= htmlspecialchars($rev['name']) ?> (<?= $rev['rating'] ?>/5)</strong>
            <p><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
            <small>🕒 <?= date('F j, Y, g:i a', strtotime($rev['created_at'])) ?></small>

            <?php if (!empty($rev['manager_response'])): ?>
                <div style="margin-top:10px; padding:10px; background:#e7f3ff; border-left: 4px solid #007bff; border-radius: 4px;">
                    <strong>Manager Response:</strong>
                    <p><?= nl2br(htmlspecialchars($rev['manager_response'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No reviews yet. Be the first to leave one!</p>
<?php endif; ?>


        <!-- Submit Review Form -->
        <form action="submit_review.php" method="POST">
            <input type="hidden" name="room_id" value="<?= $room_id ?>">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>

            <label for="rating">Rating</label>
            <select id="rating" name="rating" required>
                <option value="">Choose rating</option>
                <option value="5">⭐️⭐️⭐️⭐️⭐️ - Excellent</option>
                <option value="4">⭐️⭐️⭐️⭐️ - Good</option>
                <option value="3">⭐️⭐️⭐️ - Okay</option>
                <option value="2">⭐️⭐️ - Bad</option>
                <option value="1">⭐️ - Terrible</option>
            </select>

            <label for="comment">Comment</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>

            <button type="submit">Submit Review</button>
        </form>
    </div>

    <a href="javascript:history.back()" class="back-btn">← Back</a>
</div>

<!-- Lightbox Script -->
<div id="lightbox">
    <img id="lightbox-img" src="" alt="Preview">
</div>

<script>
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');

    document.querySelectorAll('.gallery img').forEach(img => {
        img.addEventListener('click', () => {
            lightboxImg.src = img.src;
            lightbox.style.display = 'flex';
        });
    });

    lightbox.addEventListener('click', () => {
        lightbox.style.display = 'none';
    });
</script>

</body>
</html>

<?php $dbc->close(); ?>
