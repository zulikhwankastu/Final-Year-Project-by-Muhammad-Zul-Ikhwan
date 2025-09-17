<?php
require '_db.php'; // Database connection

// Fetch all properties
$result = $dbc->query("SELECT * FROM properties ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Homestays</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .homestay-container {
            max-width: 1000px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }
        .homestay-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
        }
        .homestay-card:hover {
            transform: scale(1.02);
        }
        .homestay-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .homestay-details {
            padding: 15px 20px;
            flex-grow: 1;
        }
        .homestay-details h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #007bff;
        }
        .homestay-details p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
        }
        .homestay-details .description {
            margin-top: 8px;
            color: #333;
        }
        .view-details-btn {
            display: block;
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            border-radius: 0 0 10px 10px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .view-details-btn:hover {
            background-color: #0056b3;
        }
        .no-data {
            text-align: center;
            color: #999;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<h2>Current Homestays</h2>

<!-- Back to Dashboard button -->
<div style="max-width: 1000px; margin: 0 auto 20px auto;">
    <a href="manager_dashboard.php" style="
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    " 
    onmouseover="this.style.backgroundColor='#1e7e34';" 
    onmouseout="this.style.backgroundColor='#28a745';"
    >← Back to Dashboard</a>
</div>

<div class="homestay-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="homestay-card">
                <img src="uploads/<?= htmlspecialchars($row['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="homestay-details">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><strong>City:</strong> <?= htmlspecialchars($row['city']) ?>, <?= htmlspecialchars($row['state']) ?></p>
                    <p><strong>Zip Code:</strong> <?= htmlspecialchars($row['zip_code']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                    <p class="description"><?= htmlspecialchars($row['description']) ?></p>
                </div>
                <a class="view-details-btn" href="view_homestay_details.php?id=<?= $row['id'] ?>">View Details</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-data">No homestays found.</p>
    <?php endif; ?>
</div>

</body>
</html>
