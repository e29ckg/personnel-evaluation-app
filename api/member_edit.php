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
  if ($decoded->role_id != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
  }
} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid token']);
  exit;
}

$id = $_POST['id'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$role_id = $_POST['role_id'] ?? 2;

if (!$id || !$username || !$email) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing fields']);
  exit;
}

try {
  $stmt = $pdo->prepare("UPDATE members SET username = ?, email = ?, role_id = ? WHERE id = ?");
  $stmt->execute([$username, $email, $role_id, $id]);
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to update member']);
}
?>