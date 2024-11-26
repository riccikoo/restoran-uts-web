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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

    <div class="container mt-5">
        <h2 class="text-center fs-1 mb-4">Menu List</h2>
        <a href="add.php" class="btn btn-primary mb-3">Tambah Menu</a>\

        <div class="row">
            <?php
            include '../config/config.php';

            $sql = "SELECT * FROM menu ORDER BY menu_id ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-2 mb-4">
                        <div class="card h-100 card-hover shadow">
                            <?php if (!empty($row['menu_photo'])) { ?>
                                <img src="../<?php echo $row['menu_photo']; ?>" class="card-img-top" alt="<?php echo $row['menu_name']; ?>" style="height: 200px; object-fit: cover;">
                            <?php } else { ?>
                                <img src="../image/default.jpg" class="card-img-top" alt="Default Image" style="height: 200px; object-fit: cover;">
                            <?php } ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['menu_name']; ?></h5>
                                <p class="card-text"><?php echo $row['menu_desc']; ?></p>
                                <p class="card-text"><strong>Price: </strong>Rp. <?php echo $row['menu_price']; ?></p>
                                <p class="card-text"><strong>Status: </strong>
                                    <?php if ($row['menu_status'] == 'available') { ?>
                                        <span class="text-success">&#10003; Available</span>
                                    <?php } else { ?>
                                        <span class="text-danger">&#10007; Unavailable</span>
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="edit.php?menu_id=<?php echo $row['menu_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-menu-id="<?php echo $row['menu_id']; ?>">Delete</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>Tidak ada data</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus menu ini?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" action="backend/delete.php" method="POST">
                        <input type="hidden" name="menu_id" id="menu_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const menuId = button.getAttribute('data-menu-id');
            const menuIdInput = deleteModal.querySelector('#menu_id');
            menuIdInput.value = menuId;
        });
    </script>
</body>
</html>

