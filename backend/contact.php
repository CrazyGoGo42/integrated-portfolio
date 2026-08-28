<?php
// Contact form endpoint: validates input, stores the message in the database,
// and (optionally) sends an email notification if CONTACT_TO is configured.

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed.';
    exit;
}

require_once 'config.php';

$name    = trim($_POST['visitor_name']    ?? '');
$email   = trim($_POST['visitor_email']   ?? '');
$message = trim($_POST['visitor_message'] ?? '');

// Validation
if ($name === '' || mb_strlen($name) > 200
    || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255
    || $message === '' || mb_strlen($message) > 5000) {
    http_response_code(422);
    echo 'Please fill in your name, a valid email address, and a message.';
    exit;
}

// Store in the database
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
    );
    $stmt = $pdo->prepare(
        "INSERT INTO contact_messages (name, email, message, ip_address, user_agent)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $name,
        $email,
        $message,
        $_SERVER['REMOTE_ADDR'] ?? null,
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
} catch (PDOException $e) {
    error_log('contact.php DB error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Sorry, your message could not be saved right now. Please email actuallyme@jolinesserver.com.';
    exit;
}

// Best-effort email notification (only if a destination is configured and an MTA is available)
$to = getenv('CONTACT_TO') ?: '';
if ($to !== '' && function_exists('mail')) {
    $subject = 'Portfolio contact from ' . $name;
    $body    = "Name: {$name}\nEmail: {$email}\n\n{$message}\n";
    $headers = 'From: no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n"
             . 'Reply-To: ' . $email . "\r\n"
             . 'Content-Type: text/plain; charset=utf-8';
    @mail($to, $subject, $body, $headers);
}

echo 'Thanks ' . $name . ', your message has been received!';
