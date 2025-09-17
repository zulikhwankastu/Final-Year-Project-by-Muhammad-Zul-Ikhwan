<?php
// Include the database connection
require_once '_db.php'; // Include the connection to your database

// Start session to track the logged-in user
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs (basic validation here, you can expand as needed)
    if (!empty($email) && !empty($password)) {
        
        // First, check if the email matches the manager credentials
        if ($email === 'admin@gmail.com' && $password === 'admin123') {
            // Manager login successful
            $_SESSION['admin_id'] = 1; // You can store any value for the manager ID
            header("Location: manager_dashboard.php"); // Redirect to the manager dashboard
            exit();
        }

        // Check if the email exists in the admins table
        $sql_admin = "SELECT * FROM admins WHERE email = ?";

        if ($stmt_admin = $dbc->prepare($sql_admin)) {
            $stmt_admin->bind_param("s", $email);
            $stmt_admin->execute();
            $result_admin = $stmt_admin->get_result();

            if ($result_admin->num_rows > 0) {
                // Admin found, fetch admin data
                $admin = $result_admin->fetch_assoc();

                // Verify the password using password_verify()
                if (password_verify($password, $admin['password'])) {
                    // Admin login successful
                    $_SESSION['admin_id'] = $admin['id']; // Store admin ID in session
                    header("Location: manager_dashboard.php"); // Redirect to the admin dashboard
                    exit();
                } else {
                    $errorMessage = "Invalid password for admin.";
                }
            } else {
                // If not found in admins table, check the users table for normal users
                $sql_user = "SELECT * FROM users WHERE email = ?";

                if ($stmt_user = $dbc->prepare($sql_user)) {
                    $stmt_user->bind_param("s", $email);
                    $stmt_user->execute();
                    $result_user = $stmt_user->get_result();

                    if ($result_user->num_rows > 0) {
                        // User found, fetch user data
                        $user = $result_user->fetch_assoc();

                        // Verify the password using password_verify()
                        if (password_verify($password, $user['password'])) {
                            // Correct password, login successful
                            $_SESSION['user_id'] = $user['id']; // Store user ID in session
                            $_SESSION['email'] = $user['email']; // Store user email in session
                            header("Location: Booking/booking_dashboard.php"); // Redirect to the user dashboard
                            exit();
                        } else {
                            $errorMessage = "Invalid password.";
                        }
                    } else {
                        $errorMessage = "No user found with this email.";
                    }

                    $stmt_user->close();
                } else {
                    $errorMessage = "Error: " . $dbc->error;
                }
            }

            $stmt_admin->close();
        } else {
            $errorMessage = "Error: " . $dbc->error;
        }
    } else {
        $errorMessage = "Both fields are required.";
    }
}

// Ensure the connection is open before attempting to close it
if (isset($dbc)) {
    // Close the connection after the script finishes
    $dbc->close();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="stylesheet" href="loginstyle.css">
    <!--Google Fonts and Icons-->
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
      <form action="login.php" method="POST">
        <div class="title">Login</div>
        <?php if (isset($errorMessage)): ?>
          <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <span class="inputs">
          <span class="inputf">
            <input type="email" name="email" class="input" placeholder="Email" required />
            <span class="label">Email</span>
            <span class="material-icons icon">email</span>
          </span>
          <span class="inputf">
            <input type="password" name="password" class="input" placeholder="Password" required />
            <span class="label">Password</span>
            <span class="material-icons icon">lock</span>
          </span>
        </span>
        <br>
        <div class="text">
          Forgot password <a href="forgot_password.php">Click Here to Reset</a>
        </div>
        
        <div class="links">
          <label for="remember">
            <input type="checkbox" id="remember" />
            Remember Me
          </label>
        </div>
        <button type="submit" class="btn">
          <span>Login</span>
          <div class="dots">
            <div class="dot" style="--delay: 0s"></div>
            <div class="dot" style="--delay: 0.25s"></div>
            <div class="dot" style="--delay: 0.5s"></div>
          </div>
        </button>
        <a href="index.php" class="btn go-back-btn">
          <span>Go Back</span>
        </a>
        <div class="text">
          New user? Create an account <a href="register.php">Register</a>
        </div>
      </form>
    </div>
    <script>
      var btn = document.querySelector(".btn");
      var inputs = document.querySelectorAll(".input");
      btn.onclick = function () {
        btn.classList.toggle("active");
        setTimeout(() => {
          btn.classList.toggle("active");
          inputs[1].classList.toggle("active");
        }, 1500);
        setTimeout(() => {
          inputs[1].classList.toggle("active");
        }, 3000);
      };
    </script>
  </body>
</html>
