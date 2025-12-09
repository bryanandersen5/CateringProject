<?php
include '../Database/database.php';
session_start();

$error = "";
$success = "";

// Handle sign up form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists in admin table
        $stmt = $connection->prepare("SELECT admin_id FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email is already registered.";
            $stmt->close();
        } else {
            $stmt->close();

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'Admin'; // default role

            // Insert new admin
            $stmt = $connection->prepare("INSERT INTO admin (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $success = "Account created successfully! Redirecting to dashboard...";
                $_SESSION['admin_id'] = $stmt->insert_id;
                $_SESSION['email'] = $email;
                $stmt->close();
                header("Refresh:2; url=dashboard.php");
                exit();
            } else {
                if ($connection->errno === 1062) {
                    $error = "Email is already registered.";
                } else {
                    $error = "Error creating account. Please try again.";
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Sign Up</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="../style.css">
  <!-- <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .signup-card {
      width: 100%;
      max-width: 450px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style> -->
</head>

<body>
  <div class="login-page">
  <div class="login-card">
    <div class="card-header bg-dark text-white text-center">
      <h4>Gourmet Catering Admin Sign Up</h4>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>
      <form method="post" action="sign_up.php">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" id="name" placeholder="John Doe" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="admin@example.com" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Confirm Password</label>
          <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Re-enter password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
      </form>
    </div>
    <div class="card-footer text-center">
      <small class="text-muted">Already have an account? <a href="index.php">Login here</a></small>
    </div>
    <div class="card-footer text-center">
      <small class="text-muted">© 2025 Gourmet Catering</small>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>