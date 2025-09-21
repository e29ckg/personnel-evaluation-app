<?php include 'includes/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="text-center mb-4">สมัครสมาชิก</h3>

  <form id="register-form">
    <div class="mb-3">
      <label for="username" class="form-label">ชื่อผู้ใช้</label>
      <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">อีเมล</label>
      <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">รหัสผ่าน</label>
      <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success w-100">สมัครสมาชิก</button>
  </form>

  <div id="register-msg" class="mt-3 text-center"></div>
</div>

<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = {
    username: document.getElementById('username').value,
    email: document.getElementById('email').value,
    password: document.getElementById('password').value
  };

  axios.post('api/register.php', data).then(res => {
    document.getElementById('register-msg').innerHTML = '<span class="text-success">สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ</span>';
    setTimeout(() => window.location.href = 'login.php', 1500);
  }).catch(err => {
    document.getElementById('register-msg').innerHTML = '<span class="text-danger">เกิดข้อผิดพลาดในการสมัครสมาชิก</span>';
  });
});
</script>
<?php include 'includes/footer.php'; ?>