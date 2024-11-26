<?php
session_start();
include '../../config/config.php';

// Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'Admin') {
    // Redirect ke halaman login jika belum login atau bukan admin
    header('Location: ../../login.php');
    exit();
}

// Ambil data dari form
$userId = $_POST['user_id'];
$status = $_POST['status'] ?? null;
$role = $_POST['role'] ?? null;

// Update status jika ada
if ($status) {
    $query = "UPDATE user SET status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        // Jika prepare() gagal, tampilkan error
        die('Error preparing query (status): ' . $conn->error);
    }
    $stmt->bind_param("ss", $status, $userId);
    if (!$stmt->execute()) {
        // Jika execute() gagal, tampilkan error
        die('Error executing query (status): ' . $stmt->error);
    }
}

// Update role jika ada
if ($role) {
    $query = "UPDATE user SET user_level = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        // Jika prepare() gagal, tampilkan error
        die('Error preparing query (role): ' . $conn->error);
    }
    $stmt->bind_param("ss", $role, $userId);
    if (!$stmt->execute()) {
        // Jika execute() gagal, tampilkan error
        die('Error executing query (role): ' . $stmt->error);
    }
}

header('Location: ../user.php');
exit();
?>
