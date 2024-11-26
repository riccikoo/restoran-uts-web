<?php
session_start();
include '../config/config.php';

// Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'Admin') {
    header('Location: ../login.php');
    exit();
}

// Ambil data untuk grafik
// Jumlah Item yang Terjual Bulan Ini
$currentMonth = date('Y-m');
$query_item_count = "
    SELECT SUM(bd.quantity) AS item_count
    FROM bill_details bd
    JOIN bill b ON bd.bill_id = b.bill_id
    WHERE DATE_FORMAT(b.created_at, '%Y-%m') = '$currentMonth' AND b.bill_status = 'paid'";
$result_item_count = $conn->query($query_item_count);
$item_count_data = $result_item_count->fetch_assoc();

// Keuntungan Bulan Ini
$query_profit = "
    SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, SUM(bill_total) AS daily_profit
    FROM bill
    WHERE DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth' AND bill_status = 'paid'
    GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
    ORDER BY date ASC";
$result_profit = $conn->query($query_profit);
$profit_data = [];
while ($row = $result_profit->fetch_assoc()) {
    $profit_data[] = ['date' => $row['date'], 'profit' => $row['daily_profit']];
}

// 5 Makanan Terlaris Bulan Ini
$query_top_foods = "
    SELECT m.menu_name, SUM(bd.quantity) AS total_quantity
    FROM bill_details bd
    JOIN menu m ON bd.menu_id = m.menu_id
    JOIN bill b ON bd.bill_id = b.bill_id
    WHERE DATE_FORMAT(b.created_at, '%Y-%m') = '$currentMonth'
    GROUP BY bd.menu_id
    ORDER BY total_quantity DESC
    LIMIT 5";
$result_top_foods = $conn->query($query_top_foods);
$top_foods_data = [];
while ($row = $result_top_foods->fetch_assoc()) {
    $top_foods_data[$row['menu_name']] = $row['total_quantity'];
}

// Jumlah Menu Berdasarkan Status
$query_menu_status = "SELECT menu_status, COUNT(*) AS count FROM menu GROUP BY menu_status";
$result_menu_status = $conn->query($query_menu_status);
$menu_status_data = [];
while ($row = $result_menu_status->fetch_assoc()) {
    $menu_status_data[$row['menu_status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Warna latar belakang utama */
        body {
            background-color: #FFFBE6;
        }

        /* Navbar */
        .navbar {
            background-color: #347928;
        }
        .navbar-brand, .nav-link, .navbar-light .navbar-nav .nav-link.active {
            color: #FFFFFF !important; /* Teks navbar menjadi putih */
        }

        /* Tombol */
        .btn-primary {
            background-color: #347928;
            border-color: #347928;
        }
        .btn-primary:hover {
            background-color: #285A1E;
            border-color: #285A1E;
        }
        .btn-success {
            background-color: #C0EBA6;
            border-color: #C0EBA6;
            color: #347928;
        }
        .btn-success:hover {
            background-color: #A8D88C;
            border-color: #A8D88C;
        }

        /* Kartu dan Tabel */
        .card-custom {
            background-color: #FFFBE6;
            color: #347928;
        }
        .card {
            border-color: #C0EBA6;
        }
        .table th {
            color: #347928;
        }
        .table td {
            color: #347928;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand mb-0 h1" href="index.php">
            CikooFood
        </a>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user.php">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-2">
    <h1 class="text-center mb-4">Admin Dashboard</h1>
    
    <div class="row" style="max-height: 200px;">
        <!-- Card Jumlah Item yang Terjual Bulan Ini -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="text-center mt-5">
                    <h3 class="text-center">Jumlah Item yang Terjual Bulan Ini</h3>
                </div>
                <div class="text-center mb-5">
                    <h3><?= $item_count_data['item_count'] ?? 0 ?></h3>
                </div>
            </div>
        </div>

        <!-- Grafik 5 Makanan Terlaris -->
        <div class="col-md-6 mb-5">
            <div class="chart-container">
                <h3 class="text-center">5 Makanan Terlaris</h3>
                <canvas id="topFoodsChart" style="max-height: 180px;"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <!-- Grafik Keuntungan Bulan Ini (Full Width) -->
        <div class="col-12 mb-4">
            <div class="chart-container full-width-chart">
                <h3 class="text-center">Keuntungan Bulan Ini</h3>
                <canvas id="profitChart" style="max-height: 200px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Grafik 5 Makanan Terlaris (Bar Chart)
    var topFoodsChart = new Chart(document.getElementById('topFoodsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top_foods_data)) . '"' ?>],
            datasets: [{
                label: 'Jumlah Pesanan',
                data: [<?= implode(',', array_values($top_foods_data)) ?>],
                backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Keuntungan Bulan Ini (Line Chart)
    var profitChart = new Chart(document.getElementById('profitChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: [<?= '"' . implode('","', array_column($profit_data, 'date')) . '"' ?>],
            datasets: [{
                label: 'Keuntungan Harian',
                data: [<?= implode(',', array_column($profit_data, 'profit')) ?>],
                fill: false,
                borderColor: '#17a2b8',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>

