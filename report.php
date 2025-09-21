<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>รายงานผลการประเมิน</h3>
  <select id="round-select" class="form-select mb-3"></select>
  <div id="report-table"></div>
</div>

<script>
let selectedRound = null;

axios.get('api/rounds.php').then(res => {
  const select = document.getElementById('round-select');
  res.data.forEach(round => {
    const opt = document.createElement('option');
    opt.value = round.id;
    opt.textContent = round.name;
    select.appendChild(opt);
  });
  selectedRound = res.data[0].id;
  loadReport();
});

document.getElementById('round-select').addEventListener('change', function() {
  selectedRound = this.value;
  loadReport();
});

function loadReport() {
  axios.get('api/report.php', {
    params: { round_id: selectedRound },
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    const data = res.data;
    let html = `<table class="table table-bordered"><thead><tr><th>ชื่อ</th><th>หมวด</th><th>คะแนนเฉลี่ย</th></tr></thead><tbody>`;
    data.forEach(row => {
      html += `<tr><td>${row.full_name}</td><td>${row.category}</td><td>${row.avg_score}</td></tr>`;
    });
    html += `</tbody></table>`;
    document.getElementById('report-table').innerHTML = html;
  });
}
</script>
<?php include 'includes/footer.php'; ?>