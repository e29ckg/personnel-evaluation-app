<?php
$role_id = $_SESSION['role_id'] ?? null; // หรือจาก JWT decode
?>
<div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
  <h4 class="mb-4">เมนู</h4>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link text-white <?= $active_page === 'dashboard' ? 'fw-bold' : '' ?>" href="index.php">📊 Dashboard</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?= $active_page === 'members' ? 'fw-bold' : '' ?>" href="members.php">👥 สมาชิก</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?= $active_page === 'evaluate' ? 'fw-bold' : '' ?>" href="evaluate_form.php">📝 ประเมิน</a>
    </li>
    <?php if ($role_id == 1): // admin only ?>
    <li class="nav-item">
      <a class="nav-link text-white <?= $active_page === 'rounds' ? 'fw-bold' : '' ?>" href="rounds.php">📅 รอบประเมิน</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white <?= $active_page === 'report' ? 'fw-bold' : '' ?>" href="report_chart.php">📈 รายงาน</a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
      <a class="nav-link text-white" href="logout.php">🔓 ออกจากระบบ</a>
    </li>
  </ul>
</div>