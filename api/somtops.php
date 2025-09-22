<?php
require '../includes/jwt_guard.php';
require_once __DIR__ . '../includes/functions.php';
// $token

header('Content-Type: application/json');
// ดึงข้อมูลสมาชิก
try {
  $stmt = $pdo->prepare("
    SELECT 
      m.id, m.username, m.email,r.id AS role_id, r.name AS role_name,
      p.full_name, p.avatar_url
    FROM members m
    LEFT JOIN roles r ON m.role_id = r.id
    LEFT JOIN profiles p ON m.id = p.member_id
    ORDER BY m.id DESC
  ");
  $stmt->execute();
  $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($members);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch members']);
}
?>