<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Dashboard</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f7f4;
    margin: 0;
    padding: 40px;
    color: #2e7d32;
  }
  .dashboard-container {
    max-width: 700px;
    margin: auto;
    background: #e6f4ea;
    padding: 30px 40px;
    border-radius: 14px;
    box-shadow: 0 0 20px rgba(76, 175, 80, 0.3);
    text-align: center;
  }
  h1 {
    margin-bottom: 15px;
    font-size: 2.4rem;
  }
  p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    color: #388e3c;
  }
  .btn-book {
    background-color: #4caf50;
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
  }
  .btn-book:hover {
    background-color: #388e3c;
  }
</style>
</head>
<body>
  <div class="dashboard-container">
    <h1>Welcome to Your Dashboard</h1>
    <p>Ready to manage your bookings? Let’s get started!</p>
    <a href="booking_page.php" class="btn-book">Book Now</a>
  </div>
</body>
</html>
