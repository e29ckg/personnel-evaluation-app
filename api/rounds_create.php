<?php
require '../includes/db.php';
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = 'your_secret_key';
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? '';

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
  http_response_code(401);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

try {
  $decoded = JWT::decode($matches[1], new Key($secret_key, 'HS256'));
  $role_id = $decoded->role_id;
  if ($role_id != 1) { // สมมุติว่า role_id = 1 คือ admin
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
  }
} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid token']);
  exit;
}

$name = $_POST['name'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

if (!$name || !$start_date || !$end_date) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing required fields']);
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO evaluation_rounds (name, start_date, end_date) VALUES (?, ?, ?)");
  $stmt->execute([$name, $start_date, $end_date]);
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to create round']);
}
?>