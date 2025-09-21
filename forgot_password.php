<?php include 'includes/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="text-center mb-4">ลืมรหัสผ่าน</h3>

  <form id="forgot-form">
    <div class="mb-3">
      <label for="email" class="form-label">อีเมล</label>
      <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-danger w-100">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
  </form>

  <div id="forgot-msg" class="mt-3 text-center"></div>
</div>

<script>
document.getElementById('forgot-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = { email: document.getElementById('email').value };

  axios.post('api/forgot_password.php', data).then(res => {
    document.getElementById('forgot-msg').innerHTML = '<span class="text-success">ส่งลิงก์รีเซ็ตรหัสผ่านเรียบร้อยแล้ว</span>';
  }).catch(err => {
    document.getElementById('forgot-msg').innerHTML = '<span class="text-danger">ไม่พบอีเมลนี้</span>';
  });
});
</script>
<?php include 'includes/footer.php'; ?>