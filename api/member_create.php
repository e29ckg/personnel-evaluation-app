<?php
require '../includes/require_admin.php';
header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role_id = $_POST['role_id'] ?? 2;

if (!$username || !$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing fields']);
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO members (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
  $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role_id]);
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to create member']);
}
?>