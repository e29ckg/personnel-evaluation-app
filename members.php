<?php include 'includes/layout.php'; ?>

<div id="app" class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>รายชื่อสมาชิก</h4>
    <button class="btn btn-success" @click="showAddModal = true">➕ เพิ่มสมาชิก</button>
  </div>

  <div class="row">
    <div class="col-12 mb-2" v-for="m in members" :key="m.id">
      <div class="card">
        <div class="card-body">
          <h5>{{ m.full_name }}</h5>
          <p>{{ m.email }}</p>
          <button class="btn btn-primary btn-sm" @click="openEditModal(m)">แก้ไข</button>
          <button class="btn btn-danger btn-sm" @click="deleteMember(m.id)">ลบ</button>
          <a :href="'evaluate.php?target_id=' + m.id" class="btn btn-success btn-sm">ประเมิน</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Member Modal -->
  <div class="modal fade" ref="showAddModal" tabindex="-1" v-if="showAddModal" aria-labelledby="showEditModal" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" @submit.prevent="addMember">
        <div class="modal-header">
          <h5 class="modal-title">เพิ่มสมาชิกใหม่</h5>
          <button type="button" class="btn-close" @click="showAddModal = false"></button>
        </div>
        <div class="modal-body">
          <input v-model="form.username" class="form-control mb-2" placeholder="ชื่อผู้ใช้" required>
          <input v-model="form.email" class="form-control mb-2" placeholder="อีเมล" required>
          <input v-model="form.password" type="password" class="form-control mb-2" placeholder="รหัสผ่าน" required>
          <select v-model="form.role_id" class="form-select">
            <option value="2">ผู้ใช้ทั่วไป</option>
            <option value="1">ผู้ดูแลระบบ</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">บันทึก</button>
          <button type="button" class="btn btn-secondary" @click="showAddModal = false">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Member Modal -->
  <div class="modal fade" ref="showEditModal" id="showEditModal" tabindex="-1" aria-labelledby="showEditModal" aria-hidden="true" v-if="showEditModal">
    <div class="modal-dialog">
      <form class="modal-content" @submit.prevent="editMember">
        <div class="modal-header">
          <h5 class="modal-title">แก้ไขสมาชิก</h5>
          <button type="button" class="btn-close" @click="showEditModal = false"></button>
        </div>
        <div class="modal-body">
          <input v-model="form.username" class="form-control mb-2" required>
          <input v-model="form.email" class="form-control mb-2" required>
          <select v-model="form.role_id" class="form-select">
            <option value="2">ผู้ใช้ทั่วไป</option>
            <option value="1">ผู้ดูแลระบบ</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
          <button type="button" class="btn btn-secondary" @click="showEditModal = false">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>
</div>



<?php include 'includes/footer.php'; ?>
<script>
const { createApp } = Vue;

createApp({
  data() {
    return {
      members: [],
      form: {
        id: null,
        username: '',
        email: '',
        password: '',
        role_id: 2
      },
      showAddModal: false,
      showEditModal: false
    };
  },
  mounted() {
    this.loadMembers();
  },
  methods: {
    loadMembers() {
      axios.get('api/members.php', {
        headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
      }).then(res => {
        this.members = res.data;
      }).catch(err => {
        if (err.response?.status === 401) {
          Swal.fire({
            icon: 'warning',
            title: 'หมดเวลาการเข้าสู่ระบบ',
            text: 'กรุณาเข้าสู่ระบบใหม่'
          }).then(() => window.location.href = 'login.php');
        } else {
          Swal.fire({ icon: 'error', title: 'โหลดข้อมูลไม่สำเร็จ' });
        }
      });
    },
    openAddModal() {
      this.form = { id: null, username: '', email: '', password: '', role_id: 2 };
      this.showAddModal = true;
      this.$refs.showAddModal.modal('show');
    },
    addMember() {
      const payload = new FormData();
      for (let key in this.form) {
        payload.append(key, this.form[key]);
      }

      axios.post('api/member_create.php', payload, {
        headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
      }).then(() => {
        Swal.fire({ icon: 'success', title: 'เพิ่มสมาชิกเรียบร้อย' });
        this.showAddModal = false;
        this.loadMembers();
      }).catch(() => {
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาดในการเพิ่มสมาชิก' });
      });
    },
    openEditModal(member) {
      this.form = { ...member, password: '' };
      this.showEditModal = true;
      this.$nextTick(() => {
        const modal = new bootstrap.Modal(this.$refs.showEditModal);
        modal.show();
      });
    },
    editMember() {
      const payload = new FormData();
      for (let key in this.form) {
        if (key !== 'password') payload.append(key, this.form[key]);
      }

      axios.post('api/member_edit.php', payload, {
        headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
      }).then(() => {
        Swal.fire({ icon: 'success', title: 'แก้ไขข้อมูลเรียบร้อย' });
        this.showEditModal = false;
        this.loadMembers();
      }).catch(() => {
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาดในการแก้ไข' });
      });
    },
    deleteMember(id) {
      Swal.fire({
        icon: 'warning',
        title: 'คุณแน่ใจหรือไม่?',
        showCancelButton: true,
        confirmButtonText: 'ลบเลย',
        cancelButtonText: 'ยกเลิก'
      }).then(result => {
        if (result.isConfirmed) {
          axios.post('api/member_delete.php', { id }, {
            headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
          }).then(() => {
            Swal.fire({ icon: 'success', title: 'ลบสมาชิกเรียบร้อย' });
            this.loadMembers();
          }).catch(() => {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาดในการลบ' });
          });
        }
      });
    }
  }
}).mount('#app');
</script>
</body>
</html>