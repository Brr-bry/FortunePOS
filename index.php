<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard View</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="sidebar">
    <div class="logo">🛒</div>
    <ul>
      <li><a href="index.php"><img src="icons/home.png" alt="Home" /></a></li>
      <li><a href="order.php"><img src="icons/checkout.png" alt="Checkout" /></a></li>
      <li><a href="inventory.php"><img src="icons/inventory.png" alt="Inventory" /></a></li>
      <li><a href="users.php"><img src="icons/user.png" alt="Users" /></a></li>
      <li><a href="logout.php"><img src="icons/power.png" alt="Logout" /></a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="header">
      <h2>Retail Business Co.</h2>
      <input type="search" placeholder="Search..." />
    </div>

    <div class="dashboard-cards">
      <div class="card blue">
        <h4>Orders Today</h4>
        <h2 id="total-orders">0</h2>
      </div>
      <div class="card blue">
        <h4>Items Sold</h4>
        <h2 id="total-sales">0</h2>
      </div>
      <div class="revenue">
        <h4>Total Revenue</h4>
        <h2 class="php" id="total-revenue">0 PHP</h2>
      </div>
    </div>

    <div class="main-panel">
      <div class="left">
        <h3>Top Selling Items</h3>
        <div class="chart-row">
          <div class="chart-wrapper">
            <canvas id="pieChart"></canvas>
          </div>
          <div class="chart-wrapper">
            <canvas id="barChart"></canvas>
          </div>
        </div>

        <div class="quick-access-panel">
          <h3>Quick Access</h3>
          <a href="order.html"><button>Order</button></a>
          <a href="inventory.php"><button>Inventory</button></a>
          <a href="users.php"><button>Manage Users</button></a>
        </div>
      </div>
    </div>
  </div>

  <script>
    fetch('dashboard_data.php')
      .then(response => response.json())
      .then(data => {
        document.getElementById("total-orders").textContent = data.total_orders || 0;
        document.getElementById("total-sales").textContent = data.total_sales || 0;
        document.getElementById("total-revenue").textContent = (data.total_revenue || 0) + " PHP";

        const labels = data.top_items.map(item => item.name);
        const values = data.top_items.map(item => item.sold_qty);

        // PIE CHART
        new Chart(document.getElementById('pieChart'), {
          type: 'doughnut',
          data: {
            labels: labels,
            datasets: [{
              data: values,
              backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: { legend: { display: false } }
          }
        });

        // BAR CHART
        new Chart(document.getElementById('barChart'), {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Sold Qty',
              data: values,
              backgroundColor: '#5a7eff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true }
            },
            plugins: {
              legend: { display: false }
            }
          }
        });
      });
  </script>
</body>
</html>
