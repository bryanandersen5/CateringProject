<?php
require(__DIR__ . '/../Database/database.php');

// Add current page detection for active nav item
$currentPage = basename($_SERVER['PHP_SELF']);
$order_found = false;
$order_data = null;
$error_message = '';

function findColumn(array $columns, array $candidates) {
    foreach ($candidates as $c) {
        if (in_array($c, $columns, true)) return $c;
    }
    return null;
}

// Fetch columns available in orders table
$columnsRes = $connection->query("SHOW COLUMNS FROM orders");
$ordersColumns = [];
if ($columnsRes) {
    while ($r = $columnsRes->fetch_assoc()) {
        $ordersColumns[] = $r['Field'];
    }
}
$lastNameCandidates = ['customer_last_name', 'last_name', 'lastname', 'last'];
$phoneCandidates = ['customer_phone', 'phone', 'customer_phone_number', 'phone_number'];

$lastNameCol = findColumn($ordersColumns, $lastNameCandidates) ?: 'customer_last_name';
$phoneCol = findColumn($ordersColumns, $phoneCandidates) ?: 'customer_phone';

// Build mapping for possible columns to use and alias them
$mapCandidates = [
    'order_id' => ['order_id', 'id'],
    'customer_email' => ['customer_email', 'email'],
    'package_id' => ['package_id', 'package'],
    'total_price' => ['total_price', 'total', 'price'],
    'event_date' => ['event_date', 'date_of_event', 'catering_date'],
    'event_location' => ['event_location', 'venue', 'location'],
    'status' => ['status', 'order_status'],
    'special_requests' => ['special_requests', 'notes', 'additional_notes'],
    'created_at' => ['created_at', 'order_date', 'created']
];

$selectClauses = [];
foreach ($mapCandidates as $alias => $candidates) {
    $col = findColumn($ordersColumns, $candidates);
    if ($col) $selectClauses[] = "{$col} AS {$alias}";
}

// Make sure we always select the last name/phone for matching reasons (not displayed)
if (!in_array($lastNameCol . ' AS search_last_name', $selectClauses, true)) {
    $selectClauses[] = "{$lastNameCol} AS search_last_name";
}
if (!in_array($phoneCol . ' AS search_phone', $selectClauses, true)) {
    $selectClauses[] = "{$phoneCol} AS search_phone";
}

// Add package_id mapping if not present (for later lookup)
$hasPackageId = false;
foreach ($selectClauses as $cl) {
    if (strpos($cl, 'package_id') !== false) $hasPackageId = true;
}

// Helper to normalize phone input (strip non-digits)
function normalizeDigits($str) {
    return preg_replace('/\D+/', '', $str);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = htmlspecialchars(trim($_POST['lastName'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phoneNumber'] ?? ''));

    if (empty($last_name) || empty($phone)) {
        $error_message = "Please enter both last name and phone number.";
    } else {
        // Normalize input phone to digits only
        $phoneDigits = normalizeDigits($phone);

        // Build the SELECT using the columns we found
        $selectSql = implode(', ', $selectClauses);

        // Build where clause to compare last name exactly and phone after stripping formatting
        // The phone comparison uses REPLACE to drop common non-digit characters
        $phoneReplaceExpr = "REPLACE(REPLACE(REPLACE(REPLACE({$phoneCol}, ' ', ''), '-', ''), '(', ''), ')', '')";

        $query = "SELECT {$selectSql} FROM orders WHERE {$lastNameCol} = ? AND {$phoneReplaceExpr} = ? LIMIT 1";

        $stmt = $connection->prepare($query);
        if (!$stmt) {
            $error_message = 'Database prepare failed: ' . $connection->error;
        } else {
            $stmt->bind_param("ss", $last_name, $phoneDigits);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $order_data = $result->fetch_assoc();
                $order_found = true;

                // If we selected package_id (alias), fetch package_name if available
                if (isset($order_data['package_id']) && !empty($order_data['package_id'])) {
                    $pkgId = intval($order_data['package_id']);
                    $pkgStmt = $connection->prepare("SELECT package_name FROM catering_packages WHERE package_id = ? LIMIT 1");
                    if ($pkgStmt) {
                        $pkgStmt->bind_param('i', $pkgId);
                        $pkgStmt->execute();
                        $pkgRes = $pkgStmt->get_result();
                        if ($pkgRes && $pkgRes->num_rows > 0) {
                            $p = $pkgRes->fetch_assoc();
                            $order_data['package_name'] = $p['package_name'];
                        }
                        $pkgStmt->close();
                    }
                }
            } else {
                $error_message = "No order found with the provided information. Please check and try again.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Gourmet Catering</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">

    <!-- Page-specific spacing & heading styling -->
    <style>
        /* Space between navbar and the order tracking section */
        .navbar + .order-tracking { margin-top: 1.5rem; }

        /* Reduced / balanced heading size & weight */
        .order-tracking .tracking-wrapper h1,
        .booking-content .booking-heading {
            font-size: 1.75rem;   /* smaller, less imposing */
            font-weight: 700;     /* slightly less heavy */
            letter-spacing: -0.3px;
            margin-top: 0;
        }

        /* Optional: give subtitle a small top margin for clarity */
        .tracking-subtitle { margin-top: 0.5rem; font-weight: 400; }
    </style>
</head>
<body>
    <!-- Navigation (replaced with horizontal Bootstrap navbar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container">
            <a class="navbar-brand" href="../Index.php">Gourmet Catering</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Index.php' ? 'nav-link active' : 'nav-link'; ?>" href="../Index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Menu.php' ? 'nav-link active' : 'nav-link'; ?>" href="Menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'CateringPackages.php' ? 'nav-link active' : 'nav-link'; ?>" href="CateringPackages.php">Catering Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'QuoteRequest.php' ? 'nav-link active' : 'nav-link'; ?>" href="QuoteRequest.php">Get a quote</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Bookings.php' ? 'nav-link active' : 'nav-link'; ?>" href="Bookings.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'OrderTracking.php' ? 'nav-link active' : 'nav-link'; ?>" href="OrderTracking.php">Review Your Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Order Tracking Section -->
    <section class="order-tracking">
        <div class="container">
            <div class="tracking-wrapper">
                <h1>Track Your Order</h1>
                <p class="tracking-subtitle">Enter your details below to check the status of your catering order</p>

                <!-- Use the same container as the Bookings page -->
                <div class="booking-form-container">
                    <form id="trackingForm" class="tracking-form" method="POST">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input 
                                type="text" 
                                id="lastName" 
                                name="lastName" 
                                placeholder="Enter your last name" 
                                class="form-control custom-input"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="phoneNumber">Phone Number *</label>
                            <input 
                                type="tel" 
                                id="phoneNumber" 
                                name="phoneNumber" 
                                placeholder="Enter your phone number" 
                                class="form-control custom-input"
                                required
                            >
                        </div>

                        <button type="submit" class="btn-submit">Search Order</button>
                    </form>
                </div>

                <?php if ($order_found && $order_data): ?>
                    <div class="order-details-container">
                        <h2>Order Details</h2>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order_data['order_id'] ?? $order_data['id'] ?? ''); ?></p>
                                <p><strong>Status:</strong> <span class="status-badge"><?php echo htmlspecialchars($order_data['status'] ?? ''); ?></span></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($order_data['customer_email'] ?? $order_data['email'] ?? ''); ?></p>
                                <?php if (!empty($order_data['package_name'])): ?>
                                    <p><strong>Package:</strong> <?php echo htmlspecialchars($order_data['package_name']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <p><strong>Event Date:</strong> <?php echo !empty($order_data['event_date']) ? date('F d, Y', strtotime($order_data['event_date'])) : ''; ?></p>
                                <p><strong>Event Location:</strong> <?php echo htmlspecialchars($order_data['event_location'] ?? ''); ?></p>
                                <p><strong>Total Price:</strong> <?php echo isset($order_data['total_price']) ? '$' . number_format($order_data['total_price'], 2) : '—'; ?></p>
                                <p><strong>Order Date:</strong> <?php echo !empty($order_data['created_at']) ? date('F d, Y', strtotime($order_data['created_at'])) : ''; ?></p>
                            </div>
                        </div>

                        <?php if (!empty($order_data['special_requests'])): ?>
                            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06);">
                                <h5>Special Requests & Details</h5>
                                <pre class="form-control" style="background-color: var(--white); padding: 10px; border-radius: 4px; height: auto;"><?php echo htmlspecialchars($order_data['special_requests']); ?></pre>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Add Bootstrap JS bundle for responsive navbar toggler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>