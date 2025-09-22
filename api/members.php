<?php
require '../includes/jwt_guard.php';

header('Content-Type: application/json');

// ดึงข้อมูลสมาชิก
try {
  $stmt = $pdo->prepare("
    SELECT 
      m.id, m.username, m.email,r.id AS role_id, r.name AS role_name,
      p.full_name, p.avatar_url
    FROM members m
    LEFT JOIN roles r ON m.role_id = r.id
    LEFT JOIN profiles p ON m.id = p.member_id
    ORDER BY m.id DESC
  ");
  $stmt->execute();
  $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch members']);
}


$token = get_bearer_token();
if (!$token) {
  http_response_code(401);
  echo json_encode(['error' => 'Missing or invalid Authorization header']);
  exit;
}
// ดึงข้อมูลโปรไฟล์ผู้ใช้จาก API ภายนอก
$payload = [
  "version"=> 1,
  "perTypeId"=> "judgeStatus2",
  "judgeStatus"=> 2,
  "workStatusId"=> 1
];

$response = post_api('http://10.37.64.2:8089/jvncSearch/api/v1/search/searchPersSetupAndJudge?version=1.0&offset=0&limit=100&sort=superId&dir=asc',$payload, $token);
if (!$response) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid or expired token']);
  exit;
}

echo json_encode($response ['response']['data'] ?? []);
exit;
?>