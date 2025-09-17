<?php
require '_db.php';

$result = $dbc->query("SELECT id, full_name, email FROM users ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User List</title>
    <style>
        /* Reset some default */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 40px 20px;
            color: #333;
            position: relative;
        }
        h2 {
            text-align: center;
            color: #222;
            margin-bottom: 40px;
            font-weight: 700;
        }
        ul {
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            list-style: none;
        }
        li {
            background: white;
            margin-bottom: 15px;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        li:hover {
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
            font-size: 18px;
            display: block;
        }
        a:hover {
            text-decoration: underline;
            color: #0056b3;
        }
        /* Email in smaller, lighter text */
        .email {
            font-size: 14px;
            color: #666;
            margin-top: 4px;
        }

        /* Dashboard button styling */
        .dashboard-btn {
            position: fixed;
            top: 20px;
            left: 20px; /* Moved here */
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 22px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="manager_dashboard.php" class="dashboard-btn">Dashboard</a>

    <h2>User List</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <a href="view_user_details.php?user_id=<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['full_name']) ?>
                </a>
                <div class="email"><?= htmlspecialchars($row['email']) ?></div>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
