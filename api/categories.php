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
} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid token']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT id, name, description FROM evaluation_categories WHERE is_active = 1");
  $stmt->execute();
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($categories);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch categories']);
}
?>