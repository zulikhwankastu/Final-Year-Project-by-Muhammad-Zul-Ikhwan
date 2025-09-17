<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../_db.php';

$availableRooms = [];
$filterMode = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "SELECT * FROM rooms WHERE available = 1";
    $params = [];
    $types = '';

    if (!empty($_POST['check_in']) && !empty($_POST['check_out'])) {
        $checkIn = $_POST['check_in'];
        $checkOut = $_POST['check_out'];
        $query .= " AND id NOT IN (
            SELECT room_id FROM bookings 
            WHERE NOT (check_out <= ? OR check_in >= ?)
        )";
        $types .= 'ss';
        $params[] = $checkIn;
        $params[] = $checkOut;
        $filterMode = true;
    }

    if (!empty($_POST['guests'])) {
        $guests = intval($_POST['guests']);
        $query .= " AND capacity >= ?";
        $types .= 'i';
        $params[] = $guests;
        $filterMode = true;
    }

    if (!empty($_POST['price'])) {
        $price = floatval($_POST['price']);
        $query .= " AND price <= ?";
        $types .= 'd';
        $params[] = $price;
        $filterMode = true;
    }

    $stmt = $dbc->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $availableRooms[] = $row;
    }
    $stmt->close();
} else {
    $result = $dbc->query("SELECT * FROM rooms WHERE available = 1");
    while ($row = $result->fetch_assoc()) {
        $availableRooms[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Make a Booking</title>
<style>
    /* Reset and base */
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        background: #f0f2f5;
        color: #333;
        padding: 30px;
        min-height: 100vh;
    }
    a {
        text-decoration: none;
        color: #0066cc;
        transition: color 0.3s;
    }
    a:hover {
        color: #004a99;
    }

    /* Navigation */
    .nav {
        margin-bottom: 30px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .nav a {
        margin-right: 25px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    h2 {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 25px;
        color: #222;
    }

    /* Form Styles */
    form.search-form {
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        margin-bottom: 35px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-end;
    }
    .form-group {
        flex: 1 1 180px;
        display: flex;
        flex-direction: column;
    }
    label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #444;
        font-size: 0.95rem;
    }
    input[type="date"],
    input[type="number"],
    select {
        padding: 10px 15px;
        font-size: 1rem;
        border: 1.5px solid #ccc;
        border-radius: 8px;
        transition: border-color 0.3s;
        outline-offset: 2px;
    }
    input[type="date"]:focus,
    input[type="number"]:focus,
    select:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 6px #cce5ff;
    }

    button.btn {
        padding: 12px 25px;
        background: #007bff;
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.3s ease;
        align-self: stretch;
    }
    button.btn:hover {
        background: #0056b3;
    }

    /* Cards Container - vertical list */
    .cards-container {
         max-width: 1100px;
        margin: 0 auto;
        display: block;
    }

    /* Card Styles for vertical stacking */
    .card {
         width: 100%; /* full width of cards container */
    margin-bottom: 25px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .card-content {
        padding: 20px 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-content h4 {
        margin: 0 0 12px 0;
        font-size: 1.4rem;
        color: #222;
    }

    .card-content p {
        margin: 5px 0;
        line-height: 1.4;
        color: #555;
        font-size: 1rem;
    }

    .card-content p.capacity,
    .card-content p.price {
        font-weight: 600;
        color: #111;
    }

    /* Book Now Button in card */
    .card form {
        margin-top: 18px;
        text-align: right;
    }
    .card form button.btn {
        background: #28a745;
        padding: 10px 22px;
        font-size: 1rem;
        border-radius: 8px;
    }
    .card form button.btn:hover {
        background: #1e7e34;
    }

    /* Info text */
    .info-text {
        max-width: 900px;
        margin: 40px auto 0 auto;
        font-style: italic;
        color: #666;
        font-size: 1.1rem;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .form-row {
            flex-direction: column;
        }
        button.btn {
            width: 100%;
            align-self: center;
        }
        .card-content {
            padding: 15px 18px;
        }
    }
</style>
</head>
<body>

<div class="nav">
    <a href="booking_dashboard.php">🏠 Home</a>
    <a href="make_booking.php">📝 Make Booking</a>
   <a href="customer_manage_bookings.php">📋 Manage Booking</a>
    <a href="../view_profile_user.php">👤 Profile</a>
     <a href="../index.php">🔓 Logout</a>

   
</div>

<h2><?= $filterMode ? 'Available Rooms for Selected Criteria' : 'All Available Rooms' ?></h2>

<form method="POST" class="search-form" autocomplete="off">
    <div class="form-row">
        <div class="form-group">
            <label for="check_in">Check-In</label>
            <input id="check_in" type="date" name="check_in" value="<?= htmlspecialchars($_POST['check_in'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="check_out">Check-Out</label>
            <input id="check_out" type="date" name="check_out" value="<?= htmlspecialchars($_POST['check_out'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="guests">Guests</label>
            <select id="guests" name="guests">
                <option value="">Any</option>
                <?php
                $selectedGuests = $_POST['guests'] ?? '';
                for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?= $i ?>" <?= $selectedGuests == $i ? 'selected' : '' ?>><?= $i ?> guest<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="price">Max Price (RM)</label>
            <input id="price" type="number" step="0.01" name="price" placeholder="e.g. 300" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>
        <div class="form-group" style="flex: 0 0 130px;">
            <button class="btn" type="submit" title="Search Rooms">Search</button>
        </div>
    </div>
</form>

<?php if (empty($availableRooms)): ?>
    <p class="info-text">No rooms available for the selected criteria.</p>
<?php else: ?>
    <div class="cards-container">
    <?php foreach ($availableRooms as $room): ?>
        <div class="card">
            <img src="../<?= htmlspecialchars(explode(',', $room['image'])[0]) ?>" alt="Room Image" loading="lazy" />
            <div class="card-content">
                <h4><?= htmlspecialchars($room['name']) ?></h4>
                <p><?= htmlspecialchars($room['description']) ?></p>
                <p class="capacity">Capacity: <?= htmlspecialchars($room['capacity']) ?> guest<?= $room['capacity'] > 1 ? 's' : '' ?></p>
                <p class="price">Price per night: RM<?= number_format($room['price'], 2) ?></p>

                <?php if (!empty($_POST['check_in']) && !empty($_POST['check_out'])): ?>
                    <form method="POST" action="confirm_booking.php">
                        <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                        <input type="hidden" name="check_in" value="<?= htmlspecialchars($_POST['check_in']) ?>">
                        <input type="hidden" name="check_out" value="<?= htmlspecialchars($_POST['check_out']) ?>">
                        <input type="hidden" name="guests" value="<?= htmlspecialchars($_POST['guests'] ?? '') ?>">
                        <button class="btn" type="submit">Book Now</button>
                    </form>
                <?php else: ?>
                    <p style="font-style: italic; color: #555; margin-top: 12px;">Please enter check-in and check-out dates to book.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
