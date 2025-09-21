<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

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
  $user_id = $decoded->user_id ?? null;
  $role_id = $decoded->role_id ?? null;
} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid token']);
  exit;
}

// ตัวแปร $user_id และ $role_id พร้อมใช้งานในไฟล์ที่ include