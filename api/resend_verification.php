<?php
require '../includes/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';

$stmt = $pdo->prepare("SELECT id, email_verified FROM members WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || $user['email_verified']) {
  http_response_code(404);
  echo json_encode(['error' => 'Email not found or already verified']);
  exit;
}

$token = bin2hex(random_bytes(16));
$stmt = $pdo->prepare("UPDATE members SET email_verify_token = ? WHERE id = ?");
$stmt->execute([$token, $user['id']]);

// ส่งอีเมล (pseudo-code)
mail($email, "ยืนยันอีเมลใหม่", "คลิกเพื่อยืนยัน: https://yourdomain.com/verify_email.php?token=$token");

echo json_encode(['success' => true]);