<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>จัดการรอบการประเมิน</h3>

  <form id="round-form" class="mb-4">
    <div class="mb-2">
      <input type="text" name="name" class="form-control" placeholder="ชื่อรอบ เช่น Q3/2025" required>
    </div>
    <div class="mb-2">
      <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-2">
      <input type="date" name="end_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success w-100">เพิ่มรอบใหม่</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr><th>ชื่อรอบ</th><th>ช่วงเวลา</th></tr>
    </thead>
    <tbody id="round-list"></tbody>
  </table>
</div>

<script>
// โหลดรอบทั้งหมด
function loadRounds() {
  axios.get('api/rounds.php').then(res => {
    const tbody = document.getElementById('round-list');
    tbody.innerHTML = '';
    res.data.forEach(round => {
      tbody.innerHTML += `<tr><td>${round.name}</td><td>${round.start_date} - ${round.end_date}</td></tr>`;
    });
  });
}
loadRounds();

// เพิ่มรอบใหม่
document.getElementById('round-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  axios.post('api/rounds_create.php', formData, {
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    alert('เพิ่มรอบเรียบร้อย');
    this.reset();
    loadRounds();
  }).catch(err => {
    alert('เกิดข้อผิดพลาด');
  });
});
</script>
<?php include 'includes/footer.php'; ?>