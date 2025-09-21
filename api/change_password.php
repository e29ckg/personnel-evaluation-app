<?php
require '../includes/jwt_guard.php';

$input = json_decode(file_get_contents('php://input'), true);
$old = $input['old_password'] ?? '';
$new = $input['new_password'] ?? '';

$stmt = $pdo->prepare("SELECT password_hash FROM members WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !password_verify($old, $user['password_hash'])) {
  http_response_code(403);
  echo json_encode(['error' => 'รหัสผ่านเดิมไม่ถูกต้อง']);
  exit;
}

$stmt = $pdo->prepare("UPDATE members SET password_hash = ? WHERE id = ?");
$stmt->execute([password_hash($new, PASSWORD_DEFAULT), $user_id]);
echo json_encode(['success' => true]);