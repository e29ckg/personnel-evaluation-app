<?php
require '../includes/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';

$stmt = $pdo->prepare("SELECT id FROM members WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
  http_response_code(404);
  echo json_encode(['error' => 'Email not found']);
  exit;
}

$token = bin2hex(random_bytes(16));
$expires = date('Y-m-d H:i:s', time() + 3600); // 1 ชั่วโมง

$stmt = $pdo->prepare("UPDATE members SET reset_token = ?, reset_expires = ? WHERE id = ?");
$stmt->execute([$token, $expires, $user['id']]);

// ส่งอีเมล (pseudo-code)
mail($email, "รีเซ็ตรหัสผ่าน", "คลิกเพื่อรีเซ็ต: https://yourdomain.com/reset_password.php?token=$token");

echo json_encode(['success' => true]);