<?php include 'includes/header.php';?>
<div class="container mt-5" style="max-width: 400px;">
  <h3 class="text-center mb-4">เข้าสู่ระบบ</h3>

  <form id="login-form">
    <div class="mb-3">
      <label for="email" class="form-label">อีเมล</label>
      <input type="text" id="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">รหัสผ่าน</label>
      <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
  </form>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = {
    email: document.getElementById('email').value,
    password: document.getElementById('password').value
  };

  axios.post('api/login.php', data).then(res => {
    const token = res.data.token;
    const role_id = res.data.role_id;

    localStorage.setItem('access_token', token);

    Swal.fire({
      icon: 'success',
      title: 'เข้าสู่ระบบสำเร็จ',
      showConfirmButton: false,
      timer: 1500
    }).then(() => {
      window.location.href = 'index.php';
    });
  }).catch(err => {
    Swal.fire({
      icon: 'error',
      title: 'เข้าสู่ระบบไม่สำเร็จ',
      text: err.response?.data?.error || 'กรุณาตรวจสอบอีเมลและรหัสผ่าน',
    });
  });
});
</script>
<?php include 'includes/footer.php'; ?>