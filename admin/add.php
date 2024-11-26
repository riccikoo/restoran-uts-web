<?php
session_start();
include '../config/config.php';

// Cek apakah sesi login telah ada
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'Active') {
    header("Location: ../login.php");
    exit();
}

// Batasi akses berdasarkan user level
if ($_SESSION['user_level'] === 'Staff') {
    $current_file = basename($_SERVER['PHP_SELF']);
    if (!preg_match('/^staff\//', $current_file)) {
        header("Location: ../staff/index.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Warna sesuai kode yang diberikan */
        body {
            background-color: #FFFBE6;
        }
        
        .navbar, .btn-primary {
            background-color: #347928;
            color: #FFFBE6;
        }

        .navbar a.nav-link {
            color: #FFFBE6;
        }
        
        .navbar-brand {
            color: #FCCD2A;
            font-weight: bold;
        }
        
        h2, .modal-title {
            color: #347928;
        }

        .btn-danger {
            background-color: #C0EBA6;
            color: #347928;
            border: none;
        }

        /* Efek hover pada card */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
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
    <div class="container mt-5">
        <h2>Tambah Menu Baru</h2>
        <form method="POST" action="backend/submit.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="menu_name" class="form-label">Nama Menu</label>
                <input type="text" class="form-control" id="menu_name" name="menu_name" required>
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Foto Makanan/ Minuman</label>
                <input class="form-control" name="menu_photo" type="file" id="formFile">
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                    <option value="mak">Makanan</option>
                    <option value="min">Minuman</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="menu_desc" class="form-label">Deskripsi Menu</label>
                <input type="text" class="form-control" id="menu_desc" name="menu_desc" required>
            </div>
            <div class="mb-3">
                <label for="menu_price" class="form-label">Harga Menu</label>
                <input type="number" class="form-control" id="menu_price" name="menu_price" required>
            </div>
            <div class="mb-3">
                <label for="menu_status" class="form-label">Status Menu</label>
                <select class="form-select" id="menu_status" name="menu_status" required>
                    <option value="available">Available &#10003;</option>
                    <option value="unavailable">Tidak &#10007;</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Menu</button>
            <a href="menu.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
