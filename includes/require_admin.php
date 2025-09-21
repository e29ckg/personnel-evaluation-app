<?php
require_once __DIR__ . '/jwt_guard.php';

if (!isset($role_id) || $role_id != 1) {
  http_response_code(403);
  echo json_encode(['error' => 'Admin access required']);
  exit;
}