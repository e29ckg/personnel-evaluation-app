<?php include 'includes/layout.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
  <h4>รายชื่อสมาชิก</h4>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">➕ เพิ่มสมาชิก</button>
</div>

<!-- Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="add-member-form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">เพิ่มสมาชิกใหม่</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">ชื่อผู้ใช้</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">อีเมล</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">รหัสผ่าน</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">สิทธิ์</label>
          <select name="role_id" class="form-select">
            <option value="2">ผู้ใช้ทั่วไป</option>
            <option value="1">ผู้ดูแลระบบ</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
      </div>
    </form>
  </div>
</div>
  <div id="member-list" class="row"></div>
  <!-- Edit Member Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="edit-member-form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">แก้ไขข้อมูลสมาชิก</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        <div class="mb-2">
          <label class="form-label">ชื่อผู้ใช้</label>
          <input type="text" name="username" id="edit-username" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">อีเมล</label>
          <input type="email" name="email" id="edit-email" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">สิทธิ์</label>
          <select name="role_id" id="edit-role" class="form-select">
            <option value="2">ผู้ใช้ทั่วไป</option>
            <option value="1">ผู้ดูแลระบบ</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
      </div>
    </form>
  </div>
</div>
</div>

<script>
axios.get('api/members.php', {
  headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
}).then(res => {
  const members = res.data;
  const container = document.getElementById('member-list');
  members.forEach(m => {
    container.innerHTML += `
      <div class="col-12 mb-2">
        <div class="card">
          <div class="card-body">
            <h5>${m.full_name}</h5>
            <p>${m.email}</p>
            <a href="evaluate.php?target_id=${m.id}" class="btn btn-success btn-sm">ประเมิน</a>
          </div>
        </div>
      </div>
    `;
  });
}).catch(err => {
  alert('ไม่สามารถโหลดข้อมูลสมาชิกได้');
});

document.getElementById('add-member-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  axios.post('api/member_create.php', formData, {
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    alert('เพิ่มสมาชิกเรียบร้อยแล้ว');
    location.reload(); // หรือเรียกฟังก์ชัน refresh table
  }).catch(err => {
    alert('เกิดข้อผิดพลาดในการเพิ่มสมาชิก');
  });
});

function openEditModal(member) {
  document.getElementById('edit-id').value = member.id;
  document.getElementById('edit-username').value = member.username;
  document.getElementById('edit-email').value = member.email;
  document.getElementById('edit-role').value = member.role_id;
  new bootstrap.Modal(document.getElementById('editMemberModal')).show();
}

document.getElementById('edit-member-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  axios.post('api/member_edit.php', formData, {
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    alert('แก้ไขข้อมูลเรียบร้อย');
    location.reload();
  }).catch(err => {
    alert('เกิดข้อผิดพลาดในการแก้ไข');
  });
});

function deleteMember(id) {
  if (!confirm('คุณแน่ใจว่าต้องการลบสมาชิกนี้?')) return;

  axios.post('api/member_delete.php', { id }, {
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    alert('ลบสมาชิกเรียบร้อย');
    location.reload();
  }).catch(err => {
    alert('เกิดข้อผิดพลาดในการลบ');
  });
}

</script>
<?php include 'includes/footer.php'; ?>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>