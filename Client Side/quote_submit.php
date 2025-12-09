<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Enable error logging
error_log("Quote submit attempt - POST data: " . print_r($_POST, true));

// Include database connection (relative to Client Side folder)
$dbPath = __DIR__ . '/../Database/database.php';
if (!file_exists($dbPath)) {
    error_log("Database config not found at: $dbPath");
    echo json_encode(['success' => false, 'message' => 'Database config not found']);
    exit;
}

include $dbPath;

// Check database connection
if (!$connection) {
    error_log("Database connection failed");
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Alias $conn to $connection for consistency
$conn = $connection;

// Helper: get POST value
function post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

$name = post('full-name');
$email = post('email');
$phone = post('phone');
$message = post('message');
$event_date = post('event-date');

error_log("Quote form values - name: $name, email: $email, phone: $phone, event_date: $event_date");

// Basic validation
if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Name and email are required']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Insert into quote_requests using prepared statement
$stmt = $conn->prepare("INSERT INTO quote_requests (name, email, phone, message, event_date) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param('sssss', $name, $email, $phone, $message, $event_date);
$ok = $stmt->execute();

if (!$ok) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database insert failed: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Database insert successful
$quote_id = $conn->insert_id;
error_log("Quote inserted successfully with ID: $quote_id");
// (Reverted storing full details JSON) Quote stored in basic columns only.

// Send email to both admin email addresses
$admin_emails = ['nicafallorina@gmail.com', 'march272003@gmail.com'];

// Email subject and headers
$subject = "New Quote Request - From: $name";
$headers = "From: noreply@gourmetcatering.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "Reply-To: $email\r\n";

// Email body
$email_body = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
        .header { background-color: #667eea; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .content { background-color: white; padding: 20px; border-radius: 5px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #667eea; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h2>New Quote Request Received</h2>
        </div>
        <div class=\"content\">
            <div class=\"field\">
                <span class=\"label\">Quote Request ID:</span> #$quote_id
            </div>
            <div class=\"field\">
                <span class=\"label\">Customer Name:</span> $name
            </div>
            <div class=\"field\">
                <span class=\"label\">Email:</span> <a href=\"mailto:$email\">$email</a>
            </div>
            <div class=\"field\">
                <span class=\"label\">Phone:</span> $phone
            </div>
            <div class=\"field\">
                <span class=\"label\">Event Date:</span> $event_date
            </div>
            <div class=\"field\">
                <span class=\"label\">Message:</span><br>
                " . nl2br(htmlspecialchars($message)) . "
            </div>
            <div class=\"field\" style=\"margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;\">
                <a href=\"http://localhost/Samira%20Jimoh's%20files%20-%20Catering%20Project/Admin%20Side/manage_menu.php\" 
                   style=\"background-color: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;\">
                    View in Admin Panel
                </a>
            </div>
        </div>
        <div class=\"footer\">
            <p>This is an automated message from Gourmet Catering System.</p>
        </div>
    </div>
</body>
</html>
";

// Attempt to send email via SMTP using PHPMailer (recommended)
$smtpConfig = [
    // Replace these placeholders with your SMTP provider details
    'host' => 'smtp.gmail.com',     // e.g. smtp.gmail.com, smtp.sendgrid.net
    'port' => 587,
    'username' => 'your-email@gmail.com', // SMTP username (for Gmail use full email)
    'password' => 'your-app-password',   // SMTP password or app password
    'secure' => 'tls', // 'tls' or 'ssl'
    'from_email' => 'noreply@gourmetcatering.com',
    'from_name' => 'Gourmet Catering'
];

$sentCount = 0;
$smtpAvailable = false;
// If Composer's autoloader and PHPMailer are available, use them
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        $smtpAvailable = true;
    }
}

if ($smtpAvailable) {
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtpConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['username'];
        $mail->Password = $smtpConfig['password'];
        $mail->SMTPSecure = $smtpConfig['secure'];
        $mail->Port = $smtpConfig['port'];

        $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $email_body;

        foreach ($admin_emails as $admin_email) {
            $mail->clearAllRecipients();
            $mail->addAddress($admin_email);
            // Reply-To set to customer
            $mail->addReplyTo($email);
            $result = $mail->send();
            error_log("PHPMailer send to $admin_email: " . ($result ? 'success' : 'failed'));
            if ($result) $sentCount++;
        }
    } catch (Exception $ex) {
        error_log('PHPMailer exception: ' . $ex->getMessage());
    }
} else {
    error_log('PHPMailer not available - falling back to mail()');
}

// If PHPMailer wasn't available or none sent, fall back to PHP mail()
if (!$smtpAvailable || $sentCount === 0) {
    foreach ($admin_emails as $admin_email) {
        $mail_result = @mail($admin_email, $subject, $email_body, $headers);
        error_log("Fallback mail() sent to $admin_email: " . ($mail_result ? "success" : "failed"));
        if ($mail_result) $sentCount++;
    }
}

error_log("Total emails sent: $sentCount");

echo json_encode(['success' => true, 'message' => 'Quote request submitted successfully']);

$stmt->close();
$conn->close();
?>
