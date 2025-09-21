<?php
require '../includes/db.php';
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$input = json_decode(file_get_contents('php://input'), true);
$refresh = $input['refresh_token'] ?? '';

$stmt = $pdo->prepare("SELECT user_id, role_id FROM refresh_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$refresh]);
$user = $stmt->fetch();

if (!$user) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid refresh token']);
  exit;
}

$access_payload = [
  'user_id' => $user['user_id'],
  'role_id' => $user['role_id'],
  'exp' => time() + 3600
];
$access_token = JWT::encode($access_payload, 'your_secret_key', 'HS256');

// สร้าง refresh ใหม่ (optional)
$new_refresh = bin2hex(random_bytes(32));
$stmt = $pdo->prepare("UPDATE refresh_tokens SET token = ?, expires_at = DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE user_id = ?");
$stmt->execute([$new_refresh, $user['user_id']]);

echo json_encode([
  'access_token' => $access_token,
  'refresh_token' => $new_refresh
]);