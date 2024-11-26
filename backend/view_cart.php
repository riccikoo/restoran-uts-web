<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Keranjang kosong.";
    exit;
}

echo "<table border='1'>
        <tr>
            <th>Menu ID</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>";

$total = 0;
foreach ($_SESSION['cart'] as $menu_id => $item) {
    $item_total = $item['price'] * $item['quantity'];
    $total += $item_total;
    echo "<tr>
            <td>{$menu_id}</td>
            <td>{$item['price']}</td>
            <td>{$item['quantity']}</td>
            <td>{$item_total}</td>
            <td><button class='remove-from-cart' data-id='{$menu_id}'>Hapus</button></td>
        </tr>";
}

echo "<tr>
        <td colspan='3'>Total</td>
        <td>{$total}</td>
    </tr></table>";

?>

<script>
    $(document).ready(function() {
        $('.remove-from-cart').on('click', function() {
            const menuId = $(this).data('id');
            $.ajax({
                url: 'backend/remove_cart.php',
                method: 'POST',
                data: { menu_id: menuId },
                success: function(response) {
                    const result = JSON.parse(response);
                    alert(result.message);
                    updateCartView();
                }
            });
        });
    });
</script>
