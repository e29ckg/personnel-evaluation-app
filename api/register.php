<?php
require '../includes/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$username || !$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing fields']);
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO members (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
  $stmt->execute([
    $username,
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    2 // สมมุติว่า role_id = 2 คือผู้ใช้ทั่วไป
  ]);
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Registration failed']);
}
?>