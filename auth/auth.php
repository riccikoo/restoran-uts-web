<?php
session_start();
include '../config/config.php';

if (isset($_POST['user_email']) && isset($_POST['user_password'])) {
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    
    $query = "SELECT * FROM user WHERE user_email = '$user_email' AND user_password = '$user_password' AND status = 'Active'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        // Jika user ditemukan, login berhasil
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_level'] = $user['user_level'];
        $_SESSION['status'] = $user['status'];

        // Redirect berdasarkan user level
        if ($user['user_level'] === 'Staff') {
            header("Location: ../staff/index.php");
        } else {
            header("Location: ../admin/index.php");
        }
        exit;
    } else {
        echo "Email atau password salah, atau akun tidak aktif.";
    }
} else {
    echo "Silahkan isi email dan password.";
}
?>
