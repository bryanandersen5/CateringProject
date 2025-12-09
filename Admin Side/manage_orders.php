<?php
include '../Database/database.php';


// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $connection->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all orders
$sql = 
"SELECT o.order_id,
    COALESCE(u.name, o.customer_last_name) AS customer_name,
    COALESCE(u.email, o.customer_email) AS email,
    GROUP_CONCAT(CONCAT(mi.item_name, ' (x', oi.quantity, ')') SEPARATOR ', ') AS menu_items,
    SUM(oi.quantity) AS quantity,
    o.total_price,
    o.status
FROM orders o
LEFT JOIN users u 
    ON o.user_id = u.user_id
LEFT JOIN order_items oi 
    ON o.order_id = oi.order_id
LEFT JOIN menu_items mi 
    ON oi.item_id = mi.item_id
GROUP BY 
    o.order_id, u.name, u.email, o.total_price, o.status
ORDER BY o.order_id DESC";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Orders</title>
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
           
          <li class="nav-item"><a href="manage_orders.php" class="nav-link active">Manage Orders</a></li>
          <li class="nav-item"><a href="update_price.php" class="nav-link">Update Price</a></li>
          <li class="nav-item"><a href="confirm_orders.php" class="nav-link">Pending Orders</a></li>
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
    <h2 class="mb-4 text-center">Manage Orders</h2>

    <div class="card">
      <div class="card-header bg-secondary text-white">
        <strong>Order List</strong>
      </div>
      <div class="card-body">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Order ID</th>
              <th>Customer Name</th>
              <th>Email</th>
               
              <th>Total Price ($)</th>
              <th>Status</th>
              <th>Update</th>
            </tr>
          </thead>
          <tbody id="orderTableBody">
            <?php if ($result && $result->num_rows > 0): ?>
              <?php foreach ($result as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['order_id']) ?></td>
                  <td><?= htmlspecialchars($row['customer_name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td>$<?= htmlspecialchars(number_format($row['total_price'], 2)) ?></td>
                  <td>
                    <form method="post" action="manage_orders.php" class="d-flex">
                      <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                      <select name="status" class="form-select form-select-sm me-2">
                        <option <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option <?= $row['status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option <?= $row['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                        <option <?= $row['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                      </select>
              </td>
                      <td><button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">No orders found</td>
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