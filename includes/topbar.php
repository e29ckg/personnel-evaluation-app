<?php
// สมมุติว่าคุณใช้ session หรือ JWT decode เพื่อดึงชื่อผู้ใช้
$username = $_SESSION['username'] ?? 'ผู้ใช้ทั่วไป';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-3">
  <div class="container-fluid">
    <span class="navbar-text fw-bold">👋 สวัสดี, <?= htmlspecialchars($username) ?></span>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" href="profile_summary.php">โปรไฟล์ของฉัน</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="change_password.php">เปลี่ยนรหัสผ่าน</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="logout.php">ออกจากระบบ</a>
      </li>
    </ul>
  </div>
</nav>