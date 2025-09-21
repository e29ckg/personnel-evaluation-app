<?php
require '../includes/require_owner.php';


$member_id = $_GET['id'] ?? null;
if (!$member_id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing member ID']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT 
      m.id, m.username, m.email, r.name AS role,
      p.full_name, p.gender, p.birthdate, p.phone, p.address, p.bio, p.avatar_url
    FROM members m
    JOIN profiles p ON m.id = p.member_id
    JOIN roles r ON m.role_id = r.id
    WHERE m.id = ?
  ");
  $stmt->execute([$member_id]);
  $detail = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($detail) {
    echo json_encode($detail);
  } else {
    http_response_code(404);
    echo json_encode(['error' => 'Member not found']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch member detail']);
}
?>