<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>ประเมินสมาชิก</h3>

  <form id="evaluation-form">
    <input type="hidden" name="target_id" value="<?= $_GET['target_id'] ?? '' ?>">

    <div class="mb-3">
      <label for="round_id" class="form-label">เลือกรอบประเมิน</label>
      <select name="round_id" id="round_id" class="form-select"></select>
    </div>

    <div id="category-section"></div>

    <div class="mb-3">
      <label for="comment" class="form-label">ความคิดเห็นเพิ่มเติม</label>
      <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100">ส่งประเมิน</button>
  </form>
</div>

<script>
// โหลดรอบประเมิน
axios.get('api/rounds.php').then(res => {
  const select = document.getElementById('round_id');
  res.data.forEach(round => {
    const opt = document.createElement('option');
    opt.value = round.id;
    opt.textContent = round.name;
    select.appendChild(opt);
  });
});

// โหลดหมวดหมู่
axios.get('api/categories.php').then(res => {
  const section = document.getElementById('category-section');
  res.data.forEach(cat => {
    section.innerHTML += `
      <div class="mb-3">
        <label class="form-label">${cat.name}</label>
        <input type="hidden" name="categories[][category_id]" value="${cat.id}">
        <input type="number" name="categories[][score]" class="form-control mb-1" min="0" max="5" placeholder="คะแนน (0-5)">
        <textarea name="categories[][feedback]" class="form-control" rows="2" placeholder="Feedback"></textarea>
      </div>
    `;
  });
});

// ส่งฟอร์ม
document.getElementById('evaluation-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  axios.post('api/evaluations.php', formData, {
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    alert('บันทึกเรียบร้อย');
    window.location.href = 'members.php';
  }).catch(err => {
    alert('เกิดข้อผิดพลาดในการบันทึก');
  });
});
</script>
<?php include 'includes/footer.php'; ?>