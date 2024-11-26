<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'Active') {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_level'] === 'Staff') {
    $current_file = basename($_SERVER['PHP_SELF']);
    if (!preg_match('/^staff\//', $current_file)) {
        header("Location: ../staff/index.php");
        exit();
    }
}

// Ambil daftar user dari database
$query = "SELECT * FROM user";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <!-- Navbar -->
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
                    <a class="btn btn-danger text-white" href="../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color: #FCCD2A;">Data User</h2>

        <div class="card card-custom">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['user_id'] ?></td>
                            <td><?= $user['user_name'] ?></td>
                            <td>
                                <!-- Dropdown untuk status -->
                                <form method="POST" action="backend/update_user.php">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                    <select class="form-select" name="status" onchange="this.form.submit()">
                                        <option value="Active" <?= $user['status'] == 'Active' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="NonActive" <?= $user['status'] == 'NonActive' ? 'selected' : '' ?>>Non-Aktif</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <!-- Dropdown untuk role -->
                                <form method="POST" action="backend/update_user.php">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                    <select class="form-select" name="role" onchange="this.form.submit()">
                                        <option value="Admin" <?= $user['user_level'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="Staff" <?= $user['user_level'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
