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

$round_id = $_GET['round_id'] ?? null;
if (!$round_id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing round_id']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT 
      m.full_name,
      c.name AS category,
      ROUND(AVG(s.score), 2) AS avg_score
    FROM evaluation_scores s
    JOIN evaluation_categories c ON s.category_id = c.id
    JOIN evaluations e ON s.evaluation_id = e.id
    JOIN members m ON e.target_id = m.id
    WHERE e.round_id = ?
    GROUP BY m.id, c.id
    ORDER BY m.full_name, c.name
  ");
  $stmt->execute([$round_id]);
  $report = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($report);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to generate report']);
}
?>