<?php include 'includes/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="text-center mb-4">ส่งอีเมลยืนยันใหม่</h3>

  <form id="resend-form">
    <div class="mb-3">
      <label for="email" class="form-label">อีเมล</label>
      <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-info w-100">ส่งอีเมลยืนยัน</button>
  </form>

  <div id="resend-msg" class="mt-3 text-center"></div>
</div>

<script>
document.getElementById('resend-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = { email: document.getElementById('email').value };

  axios.post('api/resend_verification.php', data).then(res => {
    document.getElementById('resend-msg').innerHTML = '<span class="text-success">ส่งอีเมลยืนยันเรียบร้อยแล้ว</span>';
  }).catch(err => {
    document.getElementById('resend-msg').innerHTML = '<span class="text-danger">ไม่พบอีเมลนี้ หรือยืนยันแล้ว</span>';
  });
});
</script>
<?php include 'includes/footer.php'; ?>