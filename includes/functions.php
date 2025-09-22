<?php
if (!function_exists('apache_request_headers')) {

function apache_request_headers() {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
            $headers[$header] = $value;
        }
    }
    return $headers;
}
}

function get_bearer_token() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization']) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        return $matches[1];
    }
    return null;
}

function validate_jwt($token) {
    // เรียก API เพื่อตรวจสอบ token
    $ch = curl_init('http://http://10.37.64.2:8089/jvncUser/api/v1/userProfiles/-1?version=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $token,
      'Accept: application/json'
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode === 200 ? json_decode($response, true)['data'] ?? null : null;
}

function require_jwt() {
    $token = get_bearer_token();
    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'Missing token']);
        exit;
    }
    $userData = validate_jwt($token);
    if (!$userData || !isset($userData['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid or expired token']);
        exit;
    }
    return $userData; // คืนค่าข้อมูลผู้ใช้
}
function get_user_id() {
    $userData = require_jwt();
    return $userData['id'];
}
function get_role_id() {
    $userData = require_jwt();
    return $userData['role_id'] ?? null;
}

function require_admin() {
    $userData = require_jwt();
    if (($userData['role_id'] ?? 0) < 1) { // สมมติ role_id 1 ขึ้นไปคือ admin
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        exit;
    }
    return $userData;
}

function require_user() {
    $userData = require_jwt();
    if (($userData['role_id'] ?? 0) < 2) { // สมมติ role_id 2 ขึ้นไปคือ user
        http_response_code(403);
        echo json_encode(['error' => 'User access required']);
        exit;
    }
    return $userData;
}

function post_api($url, $payload, $token) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['http_code' => $httpCode, 'response' => json_decode($response, true)];
}

function get_api($url, $token) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $token,
      'Accept: application/json'
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['http_code' => $httpCode, 'response' => json_decode($response, true)];
}