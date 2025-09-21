<?php
require '../includes/db.php';
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = 'your_secret_key';
$input = json_decode(file_get_contents('php://input'), true);
$refresh_token = $input['refresh_token'] ?? '';

try {
    $decoded = JWT::decode($refresh_token, new Key($secret_key, 'HS256'));
    $user_id = $decoded->user_id;
    $role_id = $decoded->role_id;

    // ตรวจสอบว่า refresh token ยังไม่หมดอายุ
    if ($decoded->exp < time()) {
        throw new Exception('Token expired');
    }

    // สร้าง access token ใหม่
    $access_payload = [
        'user_id' => $user_id,
        'role_id' => $role_id,
        'exp' => time() + 900 // 15 นาที
    ];
    $access_token = JWT::encode($access_payload, $secret_key, 'HS256');

    echo json_encode(['access_token' => $access_token]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid refresh token']);
}
?>