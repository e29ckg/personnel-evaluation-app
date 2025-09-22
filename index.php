<?php include 'includes/layout.php'; ?>
<div class="container mt-4">
  <h3>ระบบประเมินบุคคล</h3>

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
    <a href="members.php" class="btn btn-outline-primary w-100 mb-2">ดูรายชื่อสมาชิก</a>
    <a href="report_chart.php" class="btn btn-outline-info w-100 mb-2">ดูกราฟรายงาน</a>
    <a href="profile_summary.php" class="btn btn-outline-success w-100">ดูผลการประเมินของฉัน</a>
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
  alert('ไม่สามารถโหลดข้อมูล dashboard ได้');
});
</script>
<?php include 'includes/footer.php'; ?>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>