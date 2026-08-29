<?php
// Contact form endpoint: validates input, applies spam protection, stores the
// message in the database, and (optionally) emails a notification if CONTACT_TO is set.

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

// The message a real visitor sees on success (also shown to bots we silently drop).
$successText = 'Thanks ' . ($name !== '' ? $name : 'there') . ', your message has been received!';

// --- Spam protection: honeypot ---------------------------------------------
// `website` is a hidden field. Humans never fill it; bots that fill every input do.
// Return a normal-looking success so bots don't learn they were filtered.
if (trim($_POST['website'] ?? '') !== '') {
    echo $successText;
    exit;
}

// --- Validation ------------------------------------------------------------
if ($name === '' || mb_strlen($name) > 200
    || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255
    || $message === '' || mb_strlen($message) > 5000) {
    http_response_code(422);
    echo 'Please fill in your name, a valid email address, and a message.';
    exit;
}

// --- Spam protection: link flood -------------------------------------------
// Legit enquiries rarely contain a pile of links; obvious link spam is dropped.
if (preg_match_all('#https?://#i', $message) >= 5) {
    echo $successText; // silently drop
    exit;
}

// Client IP (proxy-aware: this site is fronted by nginx, so prefer X-Forwarded-For).
function client_ip(): ?string {
    $xff = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    if ($xff !== '') {
        $first = trim(explode(',', $xff)[0]);
        if (filter_var($first, FILTER_VALIDATE_IP)) {
            return $first;
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? null;
}
$ip = client_ip();

// --- Store in the database -------------------------------------------------
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
    );

    // --- Spam protection: rate limit (max 5 per IP per 10 minutes) ---
    if ($ip !== null) {
        $rl = $pdo->prepare(
            "SELECT COUNT(*) FROM contact_messages
             WHERE ip_address = ? AND created_at > (NOW() - INTERVAL 10 MINUTE)"
        );
        $rl->execute([$ip]);
        if ((int)$rl->fetchColumn() >= 5) {
            http_response_code(429);
            echo 'You have sent several messages recently. Please try again in a little while.';
            exit;
        }
    }

    $stmt = $pdo->prepare(
        "INSERT INTO contact_messages (name, email, message, ip_address, user_agent)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $name,
        $email,
        $message,
        $ip,
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
} catch (PDOException $e) {
    error_log('contact.php DB error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Sorry, your message could not be saved right now. Please email actuallyme@jolinesserver.com.';
    exit;
}

// --- Best-effort email notification (only if configured and an MTA exists) --
$to = getenv('CONTACT_TO') ?: '';
if ($to !== '' && function_exists('mail')) {
    $subject = 'Portfolio contact from ' . $name;
    $body    = "Name: {$name}\nEmail: {$email}\n\n{$message}\n";
    $headers = 'From: no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n"
             . 'Reply-To: ' . $email . "\r\n"
             . 'Content-Type: text/plain; charset=utf-8';
    @mail($to, $subject, $body, $headers);
}

echo $successText;
