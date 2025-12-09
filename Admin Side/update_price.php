<?php
include '../Database/database.php';

// Handle price update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'], $_POST['new_price'])) {
    $package_id = intval($_POST['package_id']);
    $new_price = floatval($_POST['new_price']);

$stmt = $connection->prepare("UPDATE catering_packages SET base_price = ? WHERE package_id = ?");
    $stmt->bind_param("di", $new_price, $package_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all packages
$sql = "SELECT package_id, package_name, base_price FROM catering_packages";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Update Price</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style.css">
</head>

<body class="bg-light">

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Gourmet Catering Admin Panel</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="manage_orders.php" class="nav-link">Manage Orders</a></li>
          <li class="nav-item"><a href="update_price.php" class="nav-link active">Update Price</a></li>
          <li class="nav-item"><a href="confirm_orders.php" class="nav-link">Pending Orders</a></li>
          <li class="nav-item"><a href="order_history.php" class="nav-link">Order History</a></li>
          <li class="nav-item active">
            <a href="index.php" class="nav-link text-danger"
               onclick="alert('You have successfully logged out');">
              Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Container -->
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Update Price</h2>

    <div class="card">
      <div class="card-header bg-secondary text-white">
        <strong>Package List</strong>
      </div>
      <div class="card-body">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Package ID</th>
              <th>Package Name</th>
              <th>Package Price ($)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="orderTableBody">
            <?php if ($result && $result->num_rows > 0): ?>
              <?php foreach ($result as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['package_id']) ?></td>
                  <td><?= htmlspecialchars($row['package_name']) ?></td>
                  <td>$<?= htmlspecialchars(number_format($row['base_price'], 2)) ?></td>


                  <td>
                    <!-- Update form -->
                    <form method="post" action="update_price.php" class="d-flex">
                      <input type="hidden" name="package_id" value="<?= htmlspecialchars($row['package_id']) ?>">
                      <input type="number" step="0.01" name="new_price" class="form-control form-control-sm me-2"
                             placeholder="New price" required>
                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center">No packages found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>