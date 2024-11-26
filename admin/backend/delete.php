<?php
include '../../config/config.php';

if (isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];

    $stmt_select = $conn->prepare("SELECT menu_photo FROM menu WHERE menu_id = ?");
    $stmt_select->bind_param("s", $menu_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $stmt_select->close();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $menu_photo_path = '../../' . $row['menu_photo'];

        if (file_exists($menu_photo_path)) {
            unlink($menu_photo_path);
        }
    }

    $stmt_delete = $conn->prepare("DELETE FROM menu WHERE menu_id = ?");
    
    if ($stmt_delete) {
        $stmt_delete->bind_param("s", $menu_id);
        if ($stmt_delete->execute()) {
            header("Location: ../menu.php");
            exit();
        }
        
        $stmt_delete->close();
    }
}

$conn->close();
?>