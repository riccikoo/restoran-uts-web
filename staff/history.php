<?php
session_start();

include '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'Active') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

    /* Tabel */
    .table thead {
        background-color: #C0EBA6;
        color: #347928;
    }

    .table-bordered td, .table-bordered th {
        border: 1px solid #C0EBA6;
    }

    .table th, .table td {
        color: #347928;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #FFFBE6;
    }
    </style>
</head>
<body class="bg-light">
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
                        <a class="nav-link active" href="history.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="../auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center fs-1 mb-4">Daftar Bill🗒️</h1>
        <table class="table table-bordered align-middle text-center">
            <thead style="background-color: C0EBA6;">
                <tr>
                    <th>ID Bill</th>
                    <th>ID Meja</th>
                    <th>Nama</th>
                    <th>Total Bill</th>
                    <th>Status Bill</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query data bills dari database
                $query = "SELECT * FROM bill";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['bill_id'] . "</td>";
                        echo "<td>" . $row['table_id'] . "</td>";
                        echo "<td>" . $row['bill_name'] . "</td>";
                        echo "<td>Rp." . number_format($row['bill_total'], 0, ',', '.') . "</td>";
                        echo "<td>" . ucfirst($row['bill_status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
