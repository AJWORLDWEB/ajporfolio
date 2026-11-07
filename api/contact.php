<?php
require_once __DIR__ . '/../app/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'message'=>'Method not allowed']);
    exit;
}
if (!hash_equals(csrf_token(), (string)($_POST['_csrf'] ?? ''))) {
    http_response_code(419);
    echo json_encode(['ok'=>false,'message'=>'Session expired. Refresh and try again.']);
    exit;
}
$name = trim((string)($_POST['name'] ?? ''));
$email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
$phone = trim((string)($_POST['phone'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
$website = trim((string)($_POST['website'] ?? '')); // honeypot
if ($website !== '') { echo json_encode(['ok'=>true,'message'=>'Thank you.']); exit; }
if ($name === '' || !$email || $subject === '' || $message === '' || strlen($message) > 10000) {
    http_response_code(422);
    echo json_encode(['ok'=>false,'message'=>'Please complete the required fields with a valid email.']);
    exit;
}
$stmt = db()->prepare('INSERT INTO messages (name,email,phone,subject,message) VALUES (?,?,?,?,?)');
$stmt->execute([$name,$email,$phone,$subject,$message]);
echo json_encode(['ok'=>true,'message'=>'Your message has been received. We will get back to you soon.']);
