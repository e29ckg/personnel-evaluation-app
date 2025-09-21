<?php
require_once __DIR__ . '/jwt_guard.php';

$target_id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!isset($user_id) || !$target_id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing user or target ID']);
  exit;
}

if ($role_id != 1 && $user_id != $target_id) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied: not owner']);
  exit;
}