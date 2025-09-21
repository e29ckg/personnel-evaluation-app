<?php
require '../includes/db.php';
require '../vendor/autoload.php';
use Firebase\JWT\JWT;

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

$stmt = $pdo->prepare("SELECT id, password_hash, role_id FROM members WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
  http_response_code(401);
  echo json_encode(['error' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
  exit;
}

$payload = [
  'user_id' => $user['id'],
  'role_id' => $user['role_id'],
  'exp' => time() + 3600
];
$token = JWT::encode($payload, 'your_secret_key', 'HS256');

echo json_encode([
  'token' => $token,
  'role_id' => $user['role_id']
]);