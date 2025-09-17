<?php
// Include the database connection
require_once '_db.php'; // Include the connection to your database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs from the form
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Validate inputs (basic validation here, you can expand as needed)
    if (!empty($fullName) && !empty($email) && !empty($phone) && !empty($password)) {
        // Prepare SQL statement to insert data into the database
        $sql = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)";

        if ($stmt = $dbc->prepare($sql)) {  // Use $dbc here, not $conn
            $stmt->bind_param("ssss", $fullName, $email, $phone, $password);

            // Execute the statement
            if ($stmt->execute()) {
                // Display success message and redirect to login page
                $successMessage = "Registration successful! You can now log in.";
                header("refresh:2;url=login.php"); // Redirect after 2 seconds
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMessage = "Error: " . $dbc->error;  // Use $dbc here too
        }
    } else {
        $errorMessage = "All fields are required.";
    }
}

// Ensure the connection is open before attempting to close it
if (isset($dbc)) {  // Use $dbc here
    // Close the connection after the script finishes
    $dbc->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Form</title>
    <link rel="stylesheet" href="registerstyle.css">

    <!-- Google Fonts and Icons -->
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round|Material+Icons+Sharp|Material+Icons+Two+Tone"
      rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"/>
  </head>
  <body>
    <div class="center">
      <form action="register.php" method="POST">
        <div class="title">Register</div>
        <?php if (isset($successMessage)): ?>
          <div class="success"><?php echo $successMessage; ?></div>
        <?php elseif (isset($errorMessage)): ?>
          <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <span class="inputs">
          <span class="inputf">
            <input type="text" name="full_name" class="input" placeholder="Full Name" required />
            <span class="label">Full Name</span>
            <span class="material-icons icon">person</span>
          </span>
          <span class="inputf">
            <input type="email" name="email" class="input" placeholder="Email" required />
            <span class="label">Email</span>
            <span class="material-icons icon">email</span>
          </span>
          <span class="inputf">
            <input type="number" name="phone" class="input" placeholder="Phone Number" required />
            <span class="label">Phone Number</span>
            <span class="material-icons icon">phone</span>
          </span>
          <span class="inputf">
            <input type="password" name="password" class="input" placeholder="Password" id="password" required />
            <span class="label">Password</span>
            <span class="material-icons icon toggle-password" id="toggle-password">visibility_off</span> <!-- Eye icon for visibility -->
          </span>
          <span class="inputf">
            <input type="password" name="confirm_password" class="input" placeholder="Confirm Password" id="confirm_password" required />
            <span class="label">Confirm Password</span>
            <span class="material-icons icon toggle-password" id="toggle-confirm-password">visibility_off</span> <!-- Eye icon for visibility -->
          </span>
        </span>
        <div class="links">
          <label for="terms">
            <input type="checkbox" id="terms" required />
            I agree to the <a href="terms_and_conditions.php">terms and conditions</a>
          </label>
        </div>
        <button type="submit" class="btn">
          <span>Register</span>
          <div class="dots">
            <div class="dot" style="--delay: 0s"></div>
            <div class="dot" style="--delay: 0.25s"></div>
            <div class="dot" style="--delay: 0.5s"></div>
          </div>
        </button>
        <div class="text">
          Already have an account? <a href="login.php">Login</a>
        </div>
      </form>
    </div>
    <script>
      // Toggle password visibility for both password fields
      const togglePassword = document.getElementById('toggle-password');
      const passwordField = document.getElementById('password');
      const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
      const confirmPasswordField = document.getElementById('confirm_password');

      togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Toggle the icon visibility
        togglePassword.textContent = type === 'password' ? 'visibility_off' : 'visibility';
      });

      toggleConfirmPassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;

        // Toggle the icon visibility
        toggleConfirmPassword.textContent = type === 'password' ? 'visibility_off' : 'visibility';
      });
    </script>
  </body>
</html>
