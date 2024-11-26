<?php
include_once '../config/config.php';

$cart = json_decode($_POST['cart'], true);
$table_id = $_POST['table_id'];
$name = $_POST['name'];
$bill_date = date('dmy');
$sql_count = "SELECT COUNT(*) as count FROM bill WHERE bill_id LIKE '$bill_date-%'";
$result = $conn->query($sql_count);
$count = $result->fetch_assoc()['count'] + 1;
$bill_id = sprintf('%s-%04d', $bill_date, $count);

$total_price = array_reduce($cart, function($carry, $item) {
    return $carry + ($item['harga'] * $item['quantity']);
}, 0);
$time = date('Y-m-d H:i:s');

$sql_bill = "INSERT INTO bill (bill_id, table_id, bill_name, bill_total, bill_status,created_at) VALUES ('$bill_id', $table_id, '$name', $total_price, 'pending','$time')";
if ($conn->query($sql_bill) === TRUE) {
    // Menyimpan detail pesanan ke tabel bill_details
    $success = true;
    foreach ($cart as $item) {
        $menu_id = $item['menu_id'];
        $quantity = $item['quantity'];
        $price = $item['harga'];
        $sql_bill_details = "INSERT INTO bill_details (bill_id, menu_id, quantity, price) VALUES ('$bill_id', '$menu_id', $quantity, $price)";
        
        if (!$conn->query($sql_bill_details)) {
            $success = false;
            break;
        }
    }
    
    if ($success) {
        echo json_encode(["message" => "Checkout berhasil!"]);
    } else {
        echo json_encode(["message" => "Gagal menyimpan detail pesanan."]);
    }
} else {
    echo json_encode(["message" => "Gagal melakukan checkout."]);
}

$conn->close();
?>
