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
  $user_id = $decoded->user_id;
} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid token']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT 
      r.name AS round,
      c.name AS category,
      ROUND(AVG(s.score), 2) AS avg_score,
      GROUP_CONCAT(s.feedback SEPARATOR ' | ') AS feedbacks
    FROM evaluation_scores s
    JOIN evaluation_categories c ON s.category_id = c.id
    JOIN evaluations e ON s.evaluation_id = e.id
    JOIN evaluation_rounds r ON e.round_id = r.id
    WHERE e.target_id = ?
    GROUP BY r.id, c.id
    ORDER BY r.start_date DESC, c.name
  ");
  $stmt->execute([$user_id]);
  $summary = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($summary);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch summary']);
}
?>