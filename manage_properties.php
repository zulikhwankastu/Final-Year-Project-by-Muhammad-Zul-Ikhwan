<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Homestays</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .button {
            display: block;
            width: 100%;
            margin: 15px 0;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .back-link {
            margin-top: 30px;
            display: inline-block;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Homestays</h2>

    <a href="view_homestays.php" class="button">View Current Homestays</a>
    <a href="add_homestay.php" class="button">Add New Homestay</a>

    <a href="manager_dashboard.php" class="back-link">&larr; Back to Dashboard</a>
</div>

</body>
</html>
