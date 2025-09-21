<?php include 'includes/header.php'; ?>
<div class="container mt-4">
  <h3>กราฟรายงานผลการประเมิน</h3>

  <div class="mb-3">
    <label for="round_id" class="form-label">เลือกรอบประเมิน</label>
    <select id="round_id" class="form-select"></select>
  </div>

  <canvas id="reportChart" height="300"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart;

axios.get('api/rounds.php', {
  headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
}).then(res => {
  const select = document.getElementById('round_id');
  res.data.forEach(round => {
    const opt = document.createElement('option');
    opt.value = round.id;
    opt.textContent = round.name;
    select.appendChild(opt);
  });
  select.value = res.data[0].id;
  loadChart(select.value);
});

document.getElementById('round_id').addEventListener('change', function() {
  loadChart(this.value);
});

function loadChart(roundId) {
  axios.get('api/report.php', {
    params: { round_id: roundId },
    headers: { Authorization: 'Bearer ' + localStorage.getItem('access_token') }
  }).then(res => {
    const data = res.data;
    const labels = [...new Set(data.map(d => d.full_name))];
    const categories = [...new Set(data.map(d => d.category))];

    const datasets = categories.map(cat => {
      return {
        label: cat,
        data: labels.map(name => {
          const row = data.find(d => d.full_name === name && d.category === cat);
          return row ? row.avg_score : 0;
        }),
        backgroundColor: '#' + Math.floor(Math.random()*16777215).toString(16)
      };
    });

    if (chart) chart.destroy();
    chart = new Chart(document.getElementById('reportChart'), {
      type: 'bar',
      data: { labels, datasets },
      options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, max: 5 } }
      }
    });
  });
}
</script>
<?php include 'includes/footer.php'; ?>