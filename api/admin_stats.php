<?php
require '../includes/jwt_guard.php';

header('Content-Type: application/json');

// ดึงสถิติพื้นฐาน (ตัวอย่าง)


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