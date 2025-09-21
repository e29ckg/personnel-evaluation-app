<?php include 'includes/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="text-center mb-4">เปลี่ยนรหัสผ่าน</h3>

  <form id="change-form">
    <div class="mb-3">
      <label for="old_password" class="form-label">รหัสผ่านเดิม</label>
      <input type="password" id="old_password" name="old_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
      <input type="password" id="new_password" name="new_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-warning w-100">เปลี่ยนรหัสผ่าน</button>
  </form>
</div>

<script>
document.getElementById('change-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = {
    old_password: document.getElementById('old_password').value,
    new_password: document.getElementById('new_password').value
  };

  axios.post('api/change_password.php', data, {
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    Swal.fire({
      icon: 'success',
      title: 'เปลี่ยนรหัสผ่านสำเร็จ',
      showConfirmButton: false,
      timer: 1500
    }).then(() => {
      document.getElementById('change-form').reset();
    });
  }).catch(err => {
    Swal.fire({
      icon: 'error',
      title: 'เกิดข้อผิดพลาด',
      text: err.response?.data?.error || 'รหัสผ่านเดิมไม่ถูกต้อง',
    });
  });
});
</script>
<?php include 'includes/footer.php'; ?>