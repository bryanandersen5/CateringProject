<?php
require(__DIR__ . '/../Database/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Bookings.php');
    exit;
}

function post($k, $d = '') {
    return isset($_POST[$k]) ? trim($_POST[$k]) : $d;
}
function onlyDigits($s) {
    return preg_replace('/\D+/', '', $s);
}
function normalizeMulti($k) {
    if (!isset($_POST[$k])) return '';
    $v = $_POST[$k];
    if (!is_array($v)) return trim($v);
    $v = array_map('trim', $v);
    $v = array_filter($v, fn($x) => $x !== '');
    return implode(', ', $v);
}

// Collect inputs
$firstName = post('first_name');
$lastName = post('last_name');
$email = post('email');
$phone = onlyDigits(post('phone'));
$eventType = post('event_type');
$eventLocation = post('event_location'); // optional field added
$package = post('package'); // numeric package_id or 'custom'
$guests = intval(post('guests', 0));
$budget = post('budget') !== '' ? floatval(post('budget')) : null;
$catering_date = post('catering_date') ?: null;
$additional_notes = post('additional_notes');
$service_required = normalizeMulti('service_required');
$menu_preference = normalizeMulti('menu_preference');
$dietary_restrictions = post('dietary_restrictions');

// Basic validation
$errors = [];
if ($lastName === '') $errors[] = 'Last name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
if ($phone === '') $errors[] = 'Phone is required.';
if ($guests <= 0) $errors[] = 'Guest count must be greater than 0.';
if ($package === '') $errors[] = 'Please select a package.';

if ($errors) {
    $err = urlencode(implode(' ', $errors));
    header("Location: Bookings.php?error={$err}");
    exit;
}

// Build the special_requests text (concatenate the interesting details)
$specialRequestsParts = [];
if ($eventType !== '') $specialRequestsParts[] = "Event Type: {$eventType}";
if (!empty($service_required)) $specialRequestsParts[] = "Service Required: {$service_required}";
if (!empty($menu_preference)) $specialRequestsParts[] = "Menu Preference: {$menu_preference}";
if ($dietary_restrictions !== '') $specialRequestsParts[] = "Dietary Restrictions: {$dietary_restrictions}";
if ($additional_notes !== '') $specialRequestsParts[] = "Additional Notes: {$additional_notes}";
$specialRequestsText = implode("\n", $specialRequestsParts);

// Resolve package_id numeric (ensure numeric) and base_price
$packageId = null;
$basePrice = null;
if (is_numeric($package)) {
    $packageId = intval($package);
    $stmt = $connection->prepare("SELECT base_price FROM catering_packages WHERE package_id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('i', $packageId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $basePrice = floatval($row['base_price']);
            // If base price is 0 or less treat it as "no base price"
            if ($basePrice <= 0) {
                $basePrice = null;
            }
        } else {
            $packageId = null;
        }
        $stmt->close();
    } else {
        $packageId = null;
    }
}

// Also if the explicit 'custom' string was selected (manually-created option)
if ($package === 'custom') {
    $packageId = null;
    $basePrice = null;
}

// Determine total_price: if we have base_price and guests, use that. Else use budget if provided.
$totalPrice = 0.0;
if ($basePrice !== null && $guests > 0) {
    $totalPrice = $basePrice * $guests;
} elseif ($budget !== null) {
    $totalPrice = floatval($budget);
}

// Ensure special_requests column can store large text: if it's a VARCHAR too small, ALTER to TEXT
$colRes = $connection->query("SHOW COLUMNS FROM orders LIKE 'special_requests'");
if ($colRes && $colRes->num_rows > 0) {
    $colInfo = $colRes->fetch_assoc();
    $type = $colInfo['Type']; // e.g., varchar(255) or text
    if (preg_match('/^varchar\((\d+)\)$/i', $type, $m)) {
        $varcharLen = intval($m[1]);
        // If varchar length is less than e.g., 1000, switch to TEXT to avoid truncation
        if ($varcharLen < 1000) {
            $alterSql = "ALTER TABLE orders MODIFY `special_requests` TEXT NULL";
            if (!$connection->query($alterSql)) {
                // If alter fails due to permissions, do not block insertion; log error to file if possible
                error_log("Unable to change special_requests column to TEXT: " . $connection->error);
            }
        }
    }
}

// Define columns to insert. Keep the DB schema you shared in mind; include package_id only if known.
$insertCols = ['customer_last_name','customer_email','customer_phone','total_price','event_date','event_location','special_requests','status','created_at'];
// We'll not supply created_at to let DB set default, unless it has no default; you may adjust as needed.
$insertVals = [$lastName, $email, $phone, $totalPrice, $catering_date, $eventLocation, $specialRequestsText, 'Pending', null];

// if packageId exists, insert it (place before total_price)
if ($packageId !== null) {
    array_splice($insertCols, 3, 0, 'package_id');
    array_splice($insertVals, 3, 0, $packageId);
}

// Remove null placeholders and any created_at if needed: if created_at is handled by DB default, omit it.
$createdAtIndex = array_search('created_at', $insertCols, true);
if ($createdAtIndex !== false) {
    // Check if DB column has default CURRENT_TIMESTAMP; if so, omit created_at
    $ta = $connection->query("SHOW COLUMNS FROM orders LIKE 'created_at'");
    $col = $ta->fetch_assoc();
    $hasDefaultNow = stripos($col['Default'] ?? '', 'CURRENT_TIMESTAMP') !== false;
    if ($hasDefaultNow) {
        array_splice($insertCols, $createdAtIndex, 1);
        array_splice($insertVals, $createdAtIndex, 1);
    } else {
        // If not default, set to NOW() via SQL instead of binding param; remove from arrays and add SQL that uses NOW()
        array_splice($insertCols, $createdAtIndex, 1);
        array_splice($insertVals, $createdAtIndex, 1);
        $useNow = true;
    }
}

// Remove any NULL values left because of created_at or others.
$placeholders = [];
$bindTypes = '';
$bindValues = [];
foreach ($insertVals as $val) {
    if ($val === null) {
        // if we find a null—use a placeholder but add null as string? use 's' and bind '', better to bind null with 's' as ''
        $bindTypes .= 's';
        $bindValues[] = '';
    } elseif (is_int($val)) {
        $bindTypes .= 'i';
        $bindValues[] = $val;
    } elseif (is_float($val)) {
        $bindTypes .= 'd';
        $bindValues[] = $val;
    } else {
        $bindTypes .= 's';
        $bindValues[] = $val;
    }
    $placeholders[] = '?';
}

$cols = implode(',', $insertCols);
$ph = implode(',', $placeholders);

$sql = "INSERT INTO orders ({$cols}) VALUES ({$ph})";
$stmt = $connection->prepare($sql);
if (!$stmt) {
    $err = urlencode('DB prepare failed: ' . $connection->error);
    header("Location: Bookings.php?error={$err}");
    exit;
}

// Bind params dynamically by reference
$bindParams = [];
$bindParams[] = $bindTypes;
for ($i = 0; $i < count($bindValues); $i++) {
    $bindParams[] = &$bindValues[$i];
}
call_user_func_array([$stmt, 'bind_param'], $bindParams);

if (!$stmt->execute()) {
    $err = urlencode('DB execute failed: ' . $stmt->error);
    $stmt->close();
    header("Location: Bookings.php?error={$err}");
    exit;
}

$orderId = $stmt->insert_id;
$stmt->close();
header("Location: Bookings.php?success=order_submitted&order_id=" . urlencode($orderId));
exit;
?>
