<?php
session_start();
include("config/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $price = $_POST['price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Cek apakah menu sudah ada di keranjang
    if (isset($_SESSION['cart'][$menu_id])) {
        $_SESSION['cart'][$menu_id]['quantity']++;
    } else {
        $_SESSION['cart'][$menu_id] = [
            'price' => $price,
            'quantity' => 1
        ];
    }

    echo json_encode(['message' => 'Item added to cart']);
}
?>
