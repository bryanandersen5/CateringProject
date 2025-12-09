<?php
include '../Database/database.php';
session_start();

$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query admin by email
    $stmt = $connection->prepare("SELECT admin_id, email, password_hash FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $admin['password_hash'])) {

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['email'] = $admin['email'];
            $stmt->close();

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $stmt->close();
        $error = "No admin account found with that email.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Login</title>
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
    .login-card {
      width: 100%;
      max-width: 400px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style> -->
</head>

<body>
  <div class="card login-page">
  <div class="card login-card">
    <div class="card-header bg-dark text-white text-center">
      <h4>Gourmet Catering Admin Login</h4>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?> 
      <form method="post" action="index.php">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="admin@example.com" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
    <div class="card-footer text-center">
      <small class="text-muted">Do not have an account? <a href="sign_up.php">Sign up here</a></small>
    </div>
    <div class="card-footer text-center">
      <small class="text-muted">© 2025 Gourmet Catering</small>
    </div>
  </div>
      </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>