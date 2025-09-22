<?php
session_start();
require '../vendor/autoload.php';

header('Content-Type: application/json');

// รับข้อมูลจาก client
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

// เตรียมข้อมูลสำหรับส่งไปยัง API ภายนอก
$payload = json_encode([
  'name' => $email,
  'passwords' => $password
]);

// เรียก API ด้วย cURL
$ch = curl_init('http://10.37.64.2:8089/jvncUser/api/v1/users/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// เปิดให้รับ response header
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);
curl_close($ch);

// ดึง token จาก header (เช่น Authorization หรือ X-Access-Token)
preg_match('/Authorization:\sBearer\s(\S+)/i', $headers, $matches);
$token = $matches[1] ?? null;

if ($token) {
  http_response_code(200);
  echo json_encode([
    'token' => $token,
    'body'=> json_decode($body, true)
  ]);
  exit;
} else {
  http_response_code(401);
  echo json_encode(['error' => 'ไม่พบ token ใน header']);
  exit;
}
