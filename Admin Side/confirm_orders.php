<?php
include '../Database/database.php';

// Fetch pending orders from database
$sql =  "SELECT 
    o.order_id,
    o.customer_email,
    o.total_price,
    o.status,
    COALESCE(u.name, o.customer_last_name) AS customer_name,
    p.package_name AS package,
    COALESCE(SUM(oi.quantity), 1) AS quantity
    From orders o
    Left JOIN users u 
        ON o.user_id = u.user_id
        Left JOIN catering_packages p 
        ON o.package_id = p.package_id
        LEFT JOIN order_items oi
        ON o.order_id = oi.order_id
        WHERE o.status = 'Pending'
        GROUP BY
        o.order_id,
        o.customer_email,
        o.total_price,
        o.status,
        u.name,
        p.package_name";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Confirm Orders</title>
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
          <li class="nav-item"><a href="confirm_orders.php" class="nav-link active">Pending Orders</a></li>
          <li class="nav-item"><a href="order_history.php" class="nav-link">Order History</a></li>
          <li class="nav-item">
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
    <h2 class="mb-4 text-center">Pending Orders</h2>

    <div class="card">
      <div class="card-header bg-secondary text-white">
        <strong>Pending Orders</strong>
      </div>
      <div class="card-body">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Order ID</th>
              <th>Customer Name</th>
              <th>Email</th>
              <th>Package</th>
              <th>Quantity</th>
              <th>Total Price ($)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="orderTableBody">
            <?php if ($result && $result->num_rows > 0): ?>
              <?php foreach ($result as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['order_id']) ?></td>
                  <td><?= htmlspecialchars($row['customer_name']) ?></td>
                  <td><?= htmlspecialchars($row['customer_email']) ?></td>
                  <td><?= htmlspecialchars($row['package']) ?></td>
                  <td><?= htmlspecialchars($row['quantity']) ?></td>
                  <td>$<?= htmlspecialchars($row['total_price']) ?></td>
                  <td><?= htmlspecialchars($row['status']) ?></td>
                  <td>
                    <form method="post" action="confirm_orders.php" style="display:inline;">
                      <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                      <button type="button" class="btn btn-primary btn-sm"
                        onclick="confirmOrder(<?= htmlspecialchars($row['order_id']) ?>, this)">
                        Confirm
                      </button>

                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">No pending orders found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
function confirmOrder(orderId, button) {
  fetch('confirm_orders.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'order_id=' + encodeURIComponent(orderId)
  })
  .then(response => {
    if (response.ok) {
      // Remove the row from the table
      const row = button.closest('tr');
      row.remove();
    }
  });
}
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>