<?php
/**
 * ดึงข้อมูลผู้ใช้จาก API ภายนอกโดยอ้างอิง member_id ภายในระบบ
 *
 * @param PDO $pdo Connection ไปยังฐานข้อมูลภายใน
 * @param int $member_id รหัสสมาชิกภายในระบบ
 * @param string $apiBaseUrl URL ของ API ภายนอก (เช่น http://10.37.64.2:8089/jvncUser/api/v1)
 * @param string $accessToken JWT หรือ API token สำหรับเรียก API ภายนอก
 * @return array|null ข้อมูลผู้ใช้ หรือ null ถ้าไม่พบ
 */
function getExternalUserByMemberId(PDO $pdo, int $member_id, string $apiBaseUrl, string $accessToken): ?array
{
    // 1. หา external_user_id จากตาราง mapping
    $stmt = $pdo->prepare("
        SELECT external_user_id, source_system
        FROM external_user_refs
        WHERE member_id = ?
        LIMIT 1
    ");
    $stmt->execute([$member_id]);
    $mapping = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mapping) {
        return null; // ไม่มี mapping
    }

    $externalUserId = $mapping['external_user_id'];

    // 2. เรียก API ภายนอก
    $url = rtrim($apiBaseUrl, '/') . "/userProfiles/{$externalUserId}?version=1";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return null; // API ภายนอกตอบไม่สำเร็จ
    }

    $data = json_decode($response, true);

    // 3. คืนข้อมูลผู้ใช้ (โครงสร้างขึ้นกับ API ภายนอก)
    return $data['data'] ?? null;
}