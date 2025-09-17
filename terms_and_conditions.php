<?php
// Include header or any necessary files for the layout
// require_once 'header.php'; // Uncomment if you have a header file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your CSS file for styling -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            margin: 0;
        }

        .content {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            line-height: 1.6;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        h3 {
            font-size: 22px;
            color: #333;
            margin-top: 20px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 5px;
        }

        p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            position: relative; /* Change from fixed to relative */
            bottom: 0;
            width: 100%;
            margin-top: 30px; /* Add margin-top for separation from content */
        }

        /* Back button styling */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Terms and Conditions</h1>
    </header>
    
    <div class="content">
        <h2>Welcome to Our Service!</h2>
        <p>Please read these terms and conditions carefully before using our service.</p>

        <h3>1. Acceptance of Terms</h3>
        <p>By accessing or using our service, you agree to comply with these Terms and Conditions. If you disagree with any part, you should not use our service.</p>

        <h3>2. Services Provided</h3>
        <p>We offer various services, including but not limited to booking reservations, customer support, and other services. Our team works to ensure smooth and efficient service delivery, but we cannot guarantee uninterrupted access to the service at all times.</p>

        <h3>3. User Responsibilities</h3>
        <p>As a user, you agree to use the service only for lawful purposes. You are responsible for any activities under your account and agree not to engage in any illegal, abusive, or harmful behavior.</p>

        <h3>4. Privacy Policy</h3>
        <p>Your privacy is important to us. We collect and use personal data in accordance with our Privacy Policy. Please refer to our <a href="privacy_policy.php">Privacy Policy</a> for more details.</p>

        <h3>5. Modifications</h3>
        <p>We reserve the right to modify, suspend, or discontinue any aspect of the service at any time, with or without notice. Please check this page regularly for updates.</p>

        <h3>6. Limitation of Liability</h3>
        <p>We are not liable for any indirect, incidental, or consequential damages arising from the use or inability to use our service. Our liability is limited to the extent permitted by law.</p>

        <h3>7. Governing Law</h3>
        <p>These terms are governed by the laws of Malaysia. Any disputes arising from the use of the service will be resolved in the courts of Malaysia.</p>

        <h3>8. Contact Us</h3>
        <p>If you have any questions or concerns about these Terms and Conditions, please contact us at <a href="mailto:contact@example.com">contact@example.com</a>.</p>

        <!-- Back Button -->
        <a href="javascript:history.back()" class="back-btn">Go Back</a>
    </div>

    <footer>
        <p>&copy; 2025 Your Company. All Rights Reserved.</p>
    </footer>
</body>
</html>
