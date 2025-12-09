<?php
session_start();

// Redirect if no admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../Database/database.php';
//require('Database/database.php');
// Total Orders
$totalOrdersQuery = $connection->query("SELECT COUNT(*) AS total FROM orders");
$totalOrders = $totalOrdersQuery->fetch_assoc()['total'] ?? 0;

// Pending Orders
$pendingOrdersQuery = $connection->query("SELECT COUNT(*) AS pending FROM orders WHERE status='Pending'");
$pendingOrders = $pendingOrdersQuery->fetch_assoc()['pending'] ?? 0;

 

// Latest and new quote counts for confirmation UI
$latestQuoteQuery = $connection->query("SELECT created_at FROM quote_requests ORDER BY created_at DESC LIMIT 1");
$latestQuote = null;
if ($latestQuoteQuery) {
  $latestQuote = $latestQuoteQuery->fetch_assoc()['created_at'] ?? null;
}
$newQuotesQuery = $connection->query("SELECT COUNT(*) AS new_count FROM quote_requests WHERE status='New'");
$newQuotes = 0;
if ($newQuotesQuery) {
  $newQuotes = $newQuotesQuery->fetch_assoc()['new_count'] ?? 0;
}

// Recent Quote Requests (for admin dashboard)
$quotesResult = $connection->query("SELECT quote_id, name, email, phone, message, event_date, created_at FROM quote_requests ORDER BY created_at DESC LIMIT 5");
$recentQuotes = [];
if ($quotesResult) {
  while ($r = $quotesResult->fetch_assoc()) {
    $recentQuotes[] = $r;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="../style.css">
  <!-- <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
  </style> -->
</head>

<body class="bg-light">

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Gourmet Catering Admin Panel</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="dashboard.php" class="nav-link active">Dashboard</a></li>
          <li class="nav-item"><a href="manage_orders.php" class="nav-link">Manage Orders</a></li>
          <li class="nav-item"><a href="update_price.php" class="nav-link">Update Price</a></li>
          <li class="nav-item"><a href="confirm_orders.php" class="nav-link">Pending Orders</a></li>
          <li class="nav-item"><a href="order_history.php" class="nav-link">Order History</a></li>
          <li class="nav-item">
            <a href="index.php" class="nav-link"
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
    <h2 class="mb-4 text-center">Admin Dashboard</h2>

    <!-- Small confirmation UI showing latest quote and new count -->
    <div class="row mb-3">
      <div class="col-12">
        <div class="d-flex justify-content-center">
          <div class="alert alert-light w-100 text-center" style="max-width:900px; border:1px solid #e2e6ea;">
            <strong>Latest Quote:</strong>
            <?php if ($latestQuote): ?>
              <span class="ms-2"><?= htmlspecialchars(date('M j, Y H:i', strtotime($latestQuote))) ?></span>
            <?php else: ?>
              <span class="ms-2 text-muted">No quotes received yet</span>
            <?php endif; ?>

            <span class="ms-3">&middot;</span>

            <?php if ($newQuotes > 0): ?>
              <span class="badge bg-danger ms-3">New: <?= intval($newQuotes) ?></span>
            <?php else: ?>
              <span class="badge bg-success ms-3">No new quotes</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
  <div class="col-md-3 offset-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Total Orders</h5>
        <p class="card-text fs-4"><?= $totalOrders ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Pending Orders</h5>
        <p class="card-text fs-4"><?= $pendingOrders ?></p>
      </div>
    </div>
  </div>
</div>

      
       
    </div>

    <!-- Quick Links -->
  <div class="container">
    <div class="row g-4">
       

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-header bg-secondary text-white">Manage Orders</div>
          <div class="card-body">
            <p class="card-text">View and update customer orders.</p>
            <a href="manage_orders.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-header bg-secondary text-white">Update Price</div>
          <div class="card-body">
            <p class="card-text">Adjust menu item pricing.</p>
            <a href="update_price.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-header bg-secondary text-white">Pending Orders</div>
          <div class="card-body">
            <p class="card-text">Approve pending orders.</p>
            <a href="confirm_orders.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-header bg-secondary text-white">Order History</div>
          <div class="card-body">
            <p class="card-text">Review past orders and records.</p>
            <a href="order_history.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>
      <!-- Recent Quote Requests card -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-secondary text-white">Recent Quote Requests</div>
          <div class="card-body">
            <?php if (count($recentQuotes) === 0): ?>
              <p class="card-text">No recent quote requests.</p>
            <?php else: ?>
              <div class="list-group">
                <?php foreach ($recentQuotes as $quote): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                      <div class="fw-bold"><?= htmlspecialchars($quote['name']) ?></div>
                      <small class="text-muted"><?= htmlspecialchars($quote['email']) ?> • <?= htmlspecialchars(date('M j, Y', strtotime($quote['created_at']))) ?></small>
                    </div>
                    <div>
                      <button class="btn btn-sm btn-outline-primary view-quote-btn" data-bs-toggle="modal" data-bs-target="#quoteModal" data-name="<?= htmlspecialchars($quote['name'] ?? '', ENT_QUOTES) ?>" data-email="<?= htmlspecialchars($quote['email'] ?? '', ENT_QUOTES) ?>" data-phone="<?= htmlspecialchars($quote['phone'] ?? '', ENT_QUOTES) ?>" data-event_date="<?= htmlspecialchars($quote['event_date'] ?? '', ENT_QUOTES) ?>" data-message="<?= htmlspecialchars($quote['message'] ?? '', ENT_QUOTES) ?>">View</button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
                </div>
  </div>
  <!-- Modal for viewing full quote details -->
  <div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="quoteModalLabel">Quote Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <dl class="row">
            <dt class="col-sm-3">Name</dt>
            <dd class="col-sm-9" id="q-name"></dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9" id="q-email"></dd>

              <dt class="col-sm-3">Phone</dt>
              <dd class="col-sm-9" id="q-phone"></dd>

              <dt class="col-sm-3">Event Date</dt>
              <dd class="col-sm-9" id="q-event"></dd>

              <dt class="col-sm-3">Message</dt>
              <dd class="col-sm-9" id="q-message"></dd>
          </dl>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Fill modal with data from button attributes
    var quoteModal = document.getElementById('quoteModal');
    quoteModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      console.log('Button data:', {
        name: button.getAttribute('data-name'),
        email: button.getAttribute('data-email'),
        phone: button.getAttribute('data-phone'),
        event_date: button.getAttribute('data-event_date'),
        message: button.getAttribute('data-message')
      });
      document.getElementById('q-name').textContent = button.getAttribute('data-name') || '(not provided)';
      document.getElementById('q-email').textContent = button.getAttribute('data-email') || '(not provided)';
      document.getElementById('q-phone').textContent = button.getAttribute('data-phone') || '(not provided)';
      document.getElementById('q-event').textContent = button.getAttribute('data-event_date') || '(not provided)';
      document.getElementById('q-message').textContent = button.getAttribute('data-message') || '(not provided)';
    });
  </script>
</body>
</html>