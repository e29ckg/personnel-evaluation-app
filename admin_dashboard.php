<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>Admin Dashboard</h3>

  <div class="row text-center">
    <div class="col-md-4 mb-3">
      <div class="card">
        <div class="card-body">
          <h5>สมาชิกทั้งหมด</h5>
          <p id="member-count">-</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card">
        <div class="card-body">
          <h5>รอบประเมิน</h5>
          <p id="round-count">-</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card">
        <div class="card-body">
          <h5>การประเมินทั้งหมด</h5>
          <p id="evaluation-count">-</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <a href="members.php" class="btn btn-outline-primary w-100 mb-2">จัดการสมาชิก</a>
    <a href="rounds.php" class="btn btn-outline-success w-100 mb-2">จัดการรอบประเมิน</a>
    <a href="report_chart.php" class="btn btn-outline-info w-100">ดูกราฟรายงาน</a>
  </div>
</div>

<script>
axios.get('api/admin_stats.php', {
  headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
}).then(res => {
  document.getElementById('member-count').textContent = res.data.members;
  document.getElementById('round-count').textContent = res.data.rounds;
  document.getElementById('evaluation-count').textContent = res.data.evaluations;
}).catch(err => {
  alert('โหลดข้อมูล dashboard ไม่สำเร็จ');
});
</script>
<?php include 'includes/footer.php'; ?>