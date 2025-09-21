<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>สรุปผลการประเมินของคุณ</h3>
  <div id="summary-table"></div>
</div>

<script>
axios.get('api/evaluation_summary.php', {
  headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
}).then(res => {
  const data = res.data;
  let html = `<table class="table table-bordered"><thead><tr><th>รอบ</th><th>หมวด</th><th>คะแนนเฉลี่ย</th><th>Feedback</th></tr></thead><tbody>`;
  data.forEach(row => {
    html += `<tr>
      <td>${row.round}</td>
      <td>${row.category}</td>
      <td>${row.avg_score}</td>
      <td>${row.feedbacks}</td>
    </tr>`;
  });
  html += `</tbody></table>`;
  document.getElementById('summary-table').innerHTML = html;
}).catch(err => {
  alert('ไม่สามารถโหลดข้อมูลสรุปได้');
});
</script>
<?php include 'includes/footer.php'; ?>