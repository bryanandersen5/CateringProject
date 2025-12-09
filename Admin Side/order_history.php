<?php
include '../Database/database.php';


// Fetch finished/completed orders from database
$sql = "SELECT
    o.order_id,
    COALESCE(u.name, o.customer_last_name) AS customer_name,
    COALESCE(u.email, o.customer_email) AS email,

    cp.package_name AS package,

    o.total_price,
    o.status

FROM orders o
LEFT JOIN users u 
    ON o.user_id = u.user_id
LEFT JOIN catering_packages cp
    ON o.package_id = cp.package_id

WHERE o.status IN ('Finished', 'Completed')

GROUP BY 
    o.order_id, 
    customer_name, 
    email,  
    package, 
    o.total_price, 
    o.status

ORDER BY o.order_id DESC";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Order History</title>
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
          <li class="nav-item"><a href="update_price.php" class="nav-link">Update Price</a></li>
          <li class="nav-item"><a href="confirm_orders.php" class="nav-link">Pending Orders</a></li>
          <li class="nav-item"><a href="order_history.php" class="nav-link active">Order History</a></li>
          <li class="nav-item">
            <a href="index.php" class="nav-link text-danger"
               onclick="alert('You have successfully logged out');">
              Logout
            </a>
          </li>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Container -->
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Order History</h2>

    <div class="card">
      <div class="card-header bg-secondary text-white">
        <strong>Completed Orders</strong>
      </div>
      <div class="card-body">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Order ID</th>
              <th>Customer Name</th>
              <th>Email</th>
              <th>Package</th>
               
              <th>Total Price ($)</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="orderTableBody">
            <?php if ($result && $result->num_rows > 0): ?>
              <?php foreach ($result as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['order_id']) ?></td>
                  <td><?= htmlspecialchars($row['customer_name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['package']) ?></td>
                  
                  <td>$<?= htmlspecialchars($row['total_price']) ?></td>
                  <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">No completed orders found</td>
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