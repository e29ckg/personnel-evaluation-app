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

try {
  $members = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
  $rounds = $pdo->query("SELECT COUNT(*) FROM evaluation_rounds")->fetchColumn();
  $evaluations = $pdo->query("SELECT COUNT(*) FROM evaluations")->fetchColumn();
  echo json_encode([
    'members' => $members,
    'rounds' => $rounds,
    'evaluations' => $evaluations
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch stats']);
}
?>