<?php
session_start();
include '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_id = $_POST['menu_id'];
    $menu_name = $_POST['menu_name'];
    $menu_desc = $_POST['menu_desc'];
    $menu_price = $_POST['menu_price'];
    $menu_status = $_POST['menu_status'];
    
    $menu_photo_path = null;
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/restoran/image/";

    // Membuat direktori jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES['menu_photo']['name'])) {
        $stmt_select = $conn->prepare("SELECT menu_photo FROM menu WHERE menu_id = ?");
        $stmt_select->bind_param("s", $menu_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $stmt_select->close();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $old_image_path = $_SERVER['DOCUMENT_ROOT'] . "/restoran/" . $row['menu_photo'];

            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }

        $new_image_name = $menu_id . '.' . pathinfo($_FILES['menu_photo']['name'], PATHINFO_EXTENSION);
        $new_image_path = $target_dir . $new_image_name;

        if (move_uploaded_file($_FILES['menu_photo']['tmp_name'], $new_image_path)) {
            $menu_photo_path = 'image/' . $new_image_name;
        } else {
            die("Error uploading image: " . print_r(error_get_last(), true));
        }
    }

    if ($menu_photo_path) {
        $stmt = $conn->prepare("UPDATE menu SET menu_name = ?, menu_desc = ?, menu_price = ?, menu_status = ?, menu_photo = ? WHERE menu_id = ?");
        $stmt->bind_param("ssisss", $menu_name, $menu_desc, $menu_price, $menu_status, $menu_photo_path, $menu_id);
    } else {
        $stmt = $conn->prepare("UPDATE menu SET menu_name = ?, menu_desc = ?, menu_price = ?, menu_status = ? WHERE menu_id = ?");
        $stmt->bind_param("ssiss", $menu_name, $menu_desc, $menu_price, $menu_status, $menu_id);
    }

    if ($stmt->execute()) {
        header("Location: ../menu.php");
        exit();
    } else {
        echo "Error updating menu: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
