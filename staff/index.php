<?php
session_start();
include '../config/config.php';

// Cek apakah user sudah login dan statusnya aktif
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'Active') {
    header("Location: ../login.php");
    exit();
}

// Ambil data total uang hari ini
$query_today_sales = "SELECT SUM(bill_total) AS total_today FROM bill WHERE bill_status = 'paid' AND DATE(created_at) = CURDATE()";
$result_today_sales = mysqli_query($conn, $query_today_sales);
if (!$result_today_sales) {
    die('Query Error: ' . mysqli_error($conn));
}
$total_today = mysqli_fetch_assoc($result_today_sales)['total_today'];

// Ambil data penjualan mingguan
$query_sales_week = "SELECT DATE(created_at) AS date, SUM(bill_total) AS daily_sales 
                     FROM bill WHERE bill_status = 'paid' 
                     AND created_at >= CURDATE() - INTERVAL 7 DAY 
                     GROUP BY DATE(created_at)";
$result_sales_week = mysqli_query($conn, $query_sales_week);
if (!$result_sales_week) {
    die('Query Error: ' . mysqli_error($conn));
}
$sales_data = [];
while ($row = mysqli_fetch_assoc($result_sales_week)) {
    $sales_data[] = ['date' => $row['date'], 'daily_sales' => $row['daily_sales']];
}

// Ambil data item terlaris
$query_best_item = "SELECT menu_name, SUM(bd.quantity) AS total_sales, m.menu_photo 
                    FROM bill_details bd 
                    JOIN menu m ON bd.menu_id = m.menu_id 
                    JOIN bill b ON bd.bill_id = b.bill_id 
                    WHERE b.bill_status = 'paid' AND b.created_at >= CURDATE() - INTERVAL 7 DAY 
                    GROUP BY menu_name 
                    ORDER BY total_sales DESC LIMIT 1";
$result_best_item = mysqli_query($conn, $query_best_item);
if (!$result_best_item) {
    die('Query Error: ' . mysqli_error($conn));
}
$best_item = mysqli_fetch_assoc($result_best_item);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Warna Latar Belakang Utama */
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

    /* Kartu Total Uang Hari Ini */
    .card-success {
        background-color: #C0EBA6;
        color: #347928; /* Teks kartu menjadi hijau tua */
    }

    /* Kartu Best Item Terlaris */
    .card-info {
        background-color: #FCCD2A;
        color: #347928; /* Teks kartu menjadi hijau tua */
    }

    /* Gaya untuk elemen kartu yang lain */
    .card-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-custom img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
    }
    .card-equal-height {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 150px;
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
                        <a class="nav-link" href="payment.php">Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="../auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-2">
    <h1 class="text-center fs-1 mb-4" style="color: #347928;">Dashboard</h1>

    <div class="row mb-5 d-flex justify-content-between"> <!-- Menambahkan d-flex dan justify-content-between -->
    <div class="col-12 col-md-6">
        <div class="card card-success card-custom card-equal-height">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" height="110px" viewBox="0 -960 960 960" width="110px" fill="#347928" class="me-5"><path d="M540-420q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM220-280q-24.75 0-42.37-17.63Q160-315.25 160-340v-400q0-24.75 17.63-42.38Q195.25-800 220-800h640q24.75 0 42.38 17.62Q920-764.75 920-740v400q0 24.75-17.62 42.37Q884.75-280 860-280H220Zm100-60h440q0-42 29-71t71-29v-200q-42 0-71-29t-29-71H320q0 42-29 71t-71 29v200q42 0 71 29t29 71Zm480 180H100q-24.75 0-42.37-17.63Q40-195.25 40-220v-460h60v460h700v60ZM220-340v-400 400Z"/></svg>
                    <div>
                        <h5 class="card-title">Total Uang Hari Ini</h5>
                        <p class="card-text">Rp. <?= number_format($total_today, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card card-info card-custom card-equal-height">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img src="../<?= $best_item['menu_photo'] ?>" alt="Best Item" class="me-3">
                    <div>
                        <h5 class="card-title"><?= $best_item['menu_name'] ?></h5>
                        <p class="card-text">Terjual <?= $best_item['total_sales'] ?> kali</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>

    <div class="row mb-5 ms-5 me-5">
        <div class="col-12">
            <h5 style="color: #347928;">Grafik Penjualan Mingguan</h5>
            <canvas id="salesChart" width="200" height="40"></canvas>
        </div>
    </div>
</div>

<script>
    const salesData = <?= json_encode($sales_data) ?>;
const labels = salesData.map(item => item.date); // Menampilkan tanggal sebagai label
const data = salesData.map(item => item.daily_sales); // Menampilkan total penjualan harian

const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels, // Tanggal akan menjadi label pada sumbu X
        datasets: [{
            label: 'Penjualan Harian',
            data: data,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1,
            fill: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Tanggal'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Total Penjualan'
                },
                ticks: {
                    beginAtZero: true,
                }
            }
        }
    }
});

</script>
</body>
</html>

