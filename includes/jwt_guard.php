<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$http_response_headers = [];
$token = get_bearer_token();
if (!$token) {
  http_response_code(401);
  echo json_encode(['error' => 'Missing or invalid Authorization header']);
  exit;
}
// ดึงข้อมูลโปรไฟล์ผู้ใช้จาก API ภายนอก

$response = get_api('http://10.37.64.2:8089/jvncUser/api/v1/userProfiles/-1?version=1', $token);
if (!$response) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid or expired token']);
  exit;
}

$profile =  json_encode($response['response']['data'] ?? []);

// ตรวจสอบว่า API ส่งข้อมูลผู้ใช้กลับมาหรือไม่
if (!isset($profile) ) {
  http_response_code(401);
  echo json_encode(['error' => 'User profile not found']);
  exit;
}

