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
    $evaluator_id = $decoded->user_id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token']);
    exit;
}

$input = $_POST;
$target_id = $input['target_id'] ?? null;
$round_id = $input['round_id'] ?? null;
$comment = $input['comment'] ?? '';
$categories = json_decode($input['categories'] ?? '[]', true); // [{category_id, score, feedback}]

if (!$target_id || !$round_id || empty($categories)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("INSERT INTO evaluations (evaluator_id, target_id, round_id, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$evaluator_id, $target_id, $round_id, $comment]);
    $evaluation_id = $pdo->lastInsertId();

    $scoreStmt = $pdo->prepare("INSERT INTO evaluation_scores (evaluation_id, category_id, score, feedback) VALUES (?, ?, ?, ?)");
    foreach ($categories as $cat) {
        $scoreStmt->execute([
            $evaluation_id,
            $cat['category_id'],
            $cat['score'],
            $cat['feedback']
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save evaluation']);
}
?>