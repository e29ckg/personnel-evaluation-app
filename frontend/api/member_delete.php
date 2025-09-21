<?php
require '../includes/require_admin.php';


$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? '';

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

if (!$id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing member ID']);
  exit;
}

try {
  $stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
  $stmt->execute([$id]);
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to delete member']);
}
?>