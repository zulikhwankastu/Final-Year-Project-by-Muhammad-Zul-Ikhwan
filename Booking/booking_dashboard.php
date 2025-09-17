<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../_db.php';

// Fetch user info to display
$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['email'] ?? 'User';

// Your SQL with snake_case column names
$sql = "SELECT id, room_id, check_in, check_out, guests, total_price, payment_status, status 
        FROM bookings 
        WHERE user_id = ? 
        ORDER BY check_in ASC 
        LIMIT 5";

$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Count total bookings
$sqlCount = "SELECT COUNT(*) as total FROM bookings WHERE user_id = ?";
$stmtCount = $dbc->prepare($sqlCount);
$stmtCount->bind_param("i", $userId);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalBookings = $resultCount->fetch_assoc()['total'];
$stmtCount->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Homestay Dashboard</title>
    <style>
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f7f4;
        margin: 0;
        padding: 40px;
        color: #2e7d32;
      }
      .nav {
        margin-bottom: 20px;
        text-align: center;
      }
      .nav a {
        margin: 0 15px;
        text-decoration: none;
        font-weight: 600;
        color: #388e3c;
        font-size: 1.1rem;
      }
      .nav a:hover {
        text-decoration: underline;
      }
      .dashboard-container {
        max-width: 900px;
        margin: auto;
        background: #e6f4ea;
        padding: 30px 40px;
        border-radius: 14px;
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.3);
      }
      h1 {
        margin-bottom: 10px;
        font-size: 2.6rem;
        text-align: center;
      }
      p.subtitle {
        font-size: 1.3rem;
        text-align: center;
        margin-bottom: 40px;
        color: #388e3c;
      }
      .btn-book {
        background-color: #4caf50;
        color: white;
        padding: 15px 50px;
        border: none;
        border-radius: 8px;
        font-size: 1.3rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: block;
        width: fit-content;
        margin: 0 auto 50px;
        transition: background-color 0.3s ease;
      }
      .btn-book:hover {
        background-color: #388e3c;
      }
      .summary-cards {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-bottom: 40px;
      }
      .card {
        background: white;
        padding: 25px 40px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
        width: 250px;
        text-align: center;
      }
      .card h2 {
        margin: 0;
        font-size: 3rem;
        color: #2e7d32;
      }
      .card p {
        margin: 5px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
        color: #4caf50;
      }
      .booking-list {
        max-width: 800px;
        margin: 0 auto;
      }
      .booking-list h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #2e7d32;
      }
      .booking-list table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
      }
      .booking-list th, .booking-list td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: center;
      }
      .booking-list th {
        background-color: #4caf50;
        color: white;
        text-transform: uppercase;
      }
      .status {
        padding: 6px 12px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
        min-width: 90px;
      }
      .status.Booked {
        background-color: #4caf50;
      }
      .status.Pending {
        background-color: #f0ad4e;
      }
      .status.Cancelled {
        background-color: #d9534f;
      }
      .status.Rejected {
        background-color: #a94442;
      }
      .status.Approved {
        background-color: #5cb85c;
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

<div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($userEmail); ?>!</h1>
    <p class="subtitle">Ready to manage your bookings? Let’s get started!</p>

    <a href="make_booking.php" class="btn-book">Book Now</a>

    <div class="summary-cards">
      <div class="card">
        <h2><?php echo $totalBookings; ?></h2>
        <p>Total Bookings</p>
      </div>
    </div>

    <div class="booking-list">
      <h3>Your Upcoming Bookings</h3>
      <?php if (count($bookings) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Room ID</th>
              <th>Check-In</th>
              <th>Check-Out</th>
              <th>Guests</th>
              <th>Total Price</th>
              <th>Payment Status</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($bookings as $b): ?>
            <tr>
              <td><?php echo htmlspecialchars($b['id']); ?></td>
              <td><?php echo htmlspecialchars($b['room_id']); ?></td>
              <td><?php echo date('d M Y', strtotime($b['check_in'])); ?></td>
              <td><?php echo date('d M Y', strtotime($b['check_out'])); ?></td>
              <td><?php echo htmlspecialchars($b['guests']); ?></td>
              <td><?php echo number_format($b['total_price'], 2); ?></td>
              <td>
                <span class="status <?php echo htmlspecialchars($b['payment_status']); ?>">
                  <?php echo htmlspecialchars($b['payment_status']); ?>
                </span>
              </td>
              <td>
                <span class="status <?php echo htmlspecialchars($b['status']); ?>">
                  <?php echo htmlspecialchars($b['status']); ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align:center; color: #666;">No upcoming bookings found.</p>
      <?php endif; ?>
    </div>
</div>

</body>
</html>
