<!DOCTYPE html>
<html lang="en">
  <?php

    
  session_start();
  if (!isset($_SESSION['username'])) {
    echo "
    <div style='display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;flex-direction:column;text-align:center;'>
      <h2 style='color:#c0392b;'>Access Denied</h2>
      <p>You do not have permission to view this page.</p>
      <p style='color:#999;'>Redirecting...</p>
      <script>setTimeout(() => window.location.href = '../login.php', 5000);</script>
    </div>";
    exit();
  }
    $title = 'FortunePOS - Dashboard';
    require_once '../includes/head.php';
  ?>
<body>

<?php require_once '../includes/sidebar.php'; ?>
  
<div class="main-content">
  <?php require_once '../includes/header.php'; ?>
    <div class="dashboard-cards">
      <div class="card blue">
        <h4>Orders Today</h4>
        <h2 id="total-orders" style = 'color:white;'>0</h2>
      </div>
      <div class="card blue">
        <h4>Items Sold</h4>
        <h2 id="total-sales"  style = 'color:white;'>0</h2>
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
          <a href="./order.php"><button>Order</button></a>
          <a href="./inventory.php"><button>Inventory</button></a>
          <a href="./users.php"><button>Manage Users</button></a>
          <a href="./transactions.php"><button>Transactions</button></a>
      </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
