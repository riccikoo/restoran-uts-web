<?php
session_start();
include '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_name = $_POST['menu_name'];
    $kategori = $_POST['kategori'];
    $menu_desc = $_POST['menu_desc'];
    $menu_price = $_POST['menu_price'];
    $menu_status = $_POST['menu_status'];

    $var = ($kategori == "mak") ? "01" : "02";

    $countQuery = "SELECT MAX(menu_id) as max_menu_id FROM menu WHERE menu_id LIKE '$var-%'";
    $result = mysqli_query($conn, $countQuery);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $last_menu_id = $row['max_menu_id'];
        $jumlah = $last_menu_id ? (intval(substr($last_menu_id, 3)) + 1) : 1;
    } else {
        $jumlah = 1;
    }

    $menu_id = $var . '-' . str_pad($jumlah, 3, '0', STR_PAD_LEFT);

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/restoran/image/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . $menu_id . '.' . pathinfo($_FILES["menu_photo"]["name"], PATHINFO_EXTENSION);
    $image_path = "image/" . basename($target_file);

    if (move_uploaded_file($_FILES["menu_photo"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO menu (menu_id, menu_name, menu_desc, menu_price, menu_status, menu_photo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sssiss", $menu_id, $menu_name, $menu_desc, $menu_price, $menu_status, $image_path);

        if ($stmt->execute()) {
            header("Location: ../menu.php?success=1");
            exit();
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        echo "Error uploading image.";
    }
}

$conn->close();
?>
