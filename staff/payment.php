<?php
session_start();
include '../config/config.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'Active') {
    header("Location: ../login.php");
    exit();
}

// Function untuk mendapatkan detail bill berdasarkan nama
function getBillDetails($conn, $billName) {
    $query = "SELECT * FROM bill WHERE bill_name = ? AND bill_status = 'pending'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $billName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function untuk mendapatkan item-item dari bill
function getBillItems($conn, $billId) {
    $query = "SELECT bd.quantity, bd.price, m.menu_name 
              FROM bill_details bd 
              JOIN menu m ON bd.menu_id = m.menu_id 
              WHERE bd.bill_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $billId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['search'])) {
    $billName = $_POST['bill_name'];
    $billData = getBillDetails($conn, $billName);

    if ($billData) {
        $total = $billData['bill_total'];
        $tax = 0.10 * $total;
        $totalWithTax = $total + $tax;
        $billItems = getBillItems($conn, $billData['bill_id']);
    } else {
        $error = "Bill tidak ditemukan.";
    }
}

if (isset($_POST['pay'])) {
    $billId = $_POST['bill_id'];
    $totalWithTax = $_POST['total_with_tax'];
    $amountGiven = $_POST['amount_given'];

    // Cek apakah uang yang diberikan cukup
    if ($amountGiven < $totalWithTax) {
        $error = "Uang yang diberikan kurang. Silakan input ulang.";
    } else {
        // Hitung kembalian
        $change = $amountGiven - $totalWithTax;

        // Arahkan ke print.php setelah pembayaran berhasil
        header("Location: backend/print.php?bill_id=" . $billId . "&amount_given=" . $amountGiven . "&change=" . $change);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

<body class="bg-light">
<nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand mb-0 h1" href="index.php">
                CikooFood
            </a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="payment.php">Payment</a>
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

<div class="container mt-5">
    <div class="card card-custom p-4">
        <h2 class="text-center mb-4">Program Kasir 🛒</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (isset($success)) : ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post" class="mb-3">
            <div class="mb-3">
                <label for="bill_name" class="form-label">Cari Bill Berdasarkan Nama:</label>
                <input type="text" name="bill_name" id="bill_name" class="form-control" required>
            </div>
            <button type="submit" name="search" class="btn btn-primary w-100">Cari</button>
        </form>

        <?php if (isset($billData) && $billData) : ?>
            <div class="card mt-4 p-3">
                <h4>Detail Pembelian</h4>
                <table class="table table-borderless">
                    <tr>
                        <th>ID Bill</th>
                        <td><?php echo $billData['bill_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?php echo $billData['bill_name']; ?></td>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-center fw-bold">Detail Barang yang Dibeli</th>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($billItems as $item) : ?>
                        <tr>
                            <td><?php echo $item['menu_name']; ?></td>
                            <td>Rp. <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rp. <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th>Total Pembelian</th>
                        <td colspan="3">Rp. <?php echo number_format($billData['bill_total'], 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Pajak (10%)</th>
                        <td colspan="3">Rp. <?php echo number_format($tax, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Total dengan Pajak</th>
                        <td colspan="3">Rp. <?php echo number_format($totalWithTax, 2, ',', '.'); ?></td>
                    </tr>
                </table>
            </div>

            <div class="card mt-4 p-3">
                <form method="post">
                    <input type="hidden" name="bill_id" value="<?php echo $billData['bill_id']; ?>">
                    <input type="hidden" name="total_with_tax" value="<?php echo $totalWithTax; ?>">

                    <div class="mb-3">
                        <label for="amount_given" class="form-label">Uang Diberikan:</label>
                        <input type="number" name="amount_given" id="amount_given" class="form-control" required>
                    </div>
                    <button type="submit" name="pay" class="btn btn-success w-100">Bayar</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>