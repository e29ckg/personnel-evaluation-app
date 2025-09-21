<?php
require 'includes/db.php';

$token = $_GET['token'] ?? '';
if (!$token) {
  echo '<h3>❌ ลิงก์ไม่ถูกต้อง</h3>';
  exit;
}

$stmt = $pdo->prepare("SELECT id FROM members WHERE email_verify_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
  $stmt = $pdo->prepare("UPDATE members SET email_verified = 1, email_verify_token = NULL WHERE id = ?");
  $stmt->execute([$user['id']]);
  echo '<h3>✅ ยืนยันอีเมลเรียบร้อยแล้ว</h3>';
} else {
  echo '<h3>❌ ลิงก์หมดอายุหรือไม่ถูกต้อง</h3>';
}
?>