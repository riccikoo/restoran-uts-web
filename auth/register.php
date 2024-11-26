<?php
include '../config/config.php';

$tanggal_sekarang = date('Ymd');

$query = "SELECT COUNT(*) AS jumlah FROM user WHERE DATE(created_at) = CURDATE()";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
$jumlah_hari_ini = $data['jumlah'] + 1;

$user_id = $tanggal_sekarang . '-' . str_pad($jumlah_hari_ini, 4, '0', STR_PAD_LEFT);

$user_email = $_POST['user_email'];
$user_password = $_POST['user_password'];
$user_name = $_POST['user_name'];

$query_insert = "INSERT INTO user (user_id, user_email, user_password, user_name, created_at)
                 VALUES ('$user_id', '$user_email', '$user_password', '$user_name', NOW())";

if (mysqli_query($conn, $query_insert)) {
    header("Location: ../login.php");
} else {
    echo "Gagal menambahkan user: " . mysqli_error($conn);
}
?>
