<?php
require(__DIR__ . '/../Database/database.php');
$currentPage = basename($_SERVER['PHP_SELF']);

// Fetch recent quote requests
$query = "SELECT quote_id, name, email, phone, message, event_date, status, created_at FROM quote_requests ORDER BY created_at DESC LIMIT 100";
$result = $connection->query($query);
$quotes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $quotes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Recent Quote Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background:#f8f9fb; }
        .container { padding: 2rem 0; }
        .table-wrap { background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .truncate { max-width: 360px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Recent Quote Requests</h3>
        <a href="../Index.php" class="btn btn-outline-primary">Back to Site</a>
    </div>

    <div class="table-wrap">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Event Date</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($quotes) === 0): ?>
                        <tr><td colspan="9" class="text-center">No quote requests found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($quotes as $q): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($q['quote_id']); ?></td>
                                <td><?php echo htmlspecialchars($q['name']); ?></td>
                                <td><a href="mailto:<?php echo htmlspecialchars($q['email']); ?>"><?php echo htmlspecialchars($q['email']); ?></a></td>
                                <td><?php echo htmlspecialchars($q['phone']); ?></td>
                                <td><?php echo $q['event_date'] ? htmlspecialchars($q['event_date']) : '<em>—</em>'; ?></td>
                                <td class="truncate" title="<?php echo htmlspecialchars($q['message']); ?>"><?php echo htmlspecialchars(strlen($q['message']) > 100 ? substr($q['message'],0,100) . '...' : $q['message']); ?></td>
                                <td><?php echo htmlspecialchars($q['status']); ?></td>
                                <td><?php echo htmlspecialchars($q['created_at']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal" 
                                        data-id="<?php echo htmlspecialchars($q['quote_id']); ?>" 
                                        data-name="<?php echo htmlspecialchars($q['name']); ?>" 
                                        data-email="<?php echo htmlspecialchars($q['email']); ?>" 
                                        data-phone="<?php echo htmlspecialchars($q['phone']); ?>" 
                                        data-event="<?php echo htmlspecialchars($q['event_date']); ?>" 
                                        data-message="<?php echo htmlspecialchars($q['message']); ?>" 
                                        data-created="<?php echo htmlspecialchars($q['created_at']); ?>">
                                        View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Quote Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-3">ID</dt>
          <dd class="col-sm-9" id="q-id"></dd>

          <dt class="col-sm-3">Name</dt>
          <dd class="col-sm-9" id="q-name"></dd>

          <dt class="col-sm-3">Email</dt>
          <dd class="col-sm-9" id="q-email"></dd>

          <dt class="col-sm-3">Phone</dt>
          <dd class="col-sm-9" id="q-phone"></dd>

          <dt class="col-sm-3">Event Date</dt>
          <dd class="col-sm-9" id="q-event"></dd>

          <dt class="col-sm-3">Received</dt>
          <dd class="col-sm-9" id="q-created"></dd>

          <dt class="col-sm-3">Message</dt>
          <dd class="col-sm-9" id="q-message" style="white-space:pre-wrap"></dd>
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
    var viewModal = document.getElementById('viewModal');
    viewModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('q-id').textContent = button.getAttribute('data-id');
        document.getElementById('q-name').textContent = button.getAttribute('data-name');
        document.getElementById('q-email').innerHTML = '<a href="mailto:' + button.getAttribute('data-email') + '">' + button.getAttribute('data-email') + '</a>';
        document.getElementById('q-phone').textContent = button.getAttribute('data-phone');
        document.getElementById('q-event').textContent = button.getAttribute('data-event') || '—';
        document.getElementById('q-created').textContent = button.getAttribute('data-created');
        document.getElementById('q-message').textContent = button.getAttribute('data-message');
    });
</script>
</body>
</html>
