<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #FFFBE6;
        }
        
        h1, h2 {
            color: #347928;
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .navbar-custom {
            background-color: #347928;
            color: white;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
        }

        .navbar-custom .nav-link:hover {
            color: #C0EBA6;
        }

        .card-body {
            background-color: #FFFBE6;
        }

        .btn-primary {
            background-color: #FCCD2A;
            border-color: #FCCD2A;
        }

        .btn-primary:hover {
            background-color: #C0EBA6;
            border-color: #C0EBA6;
        }

        .btn-success {
            background-color: #347928;
            border-color: #347928;
        }

        .btn-success:hover {
            background-color: #2D6A2F;
            border-color: #2D6A2F;
        }

        .list-group-item {
            background-color: #FFFBE6;
            border-color: #FCCD2A;
        }

        .list-group-item:hover {
            background-color: #FCCD2A;
            cursor: pointer;
        }

        #cart-section {
            position: sticky;
            top: 20px;
            background-color: #FFFBE6;
            border: 1px solid #FCCD2A;
            padding: 20px;
            border-radius: 8px;
        }

        #table-selection select, #name {
            border-radius: 5px;
        }

        #table-selection {
            background-color: #C0EBA6;
            padding: 15px;
            border-radius: 8px;
        }

        .container {
            margin-top: 30px;
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center fs-1 mb-4">🍴Menu🍽️</h1>
        <div class="row">
            <!-- Bagian menu di sebelah kiri -->
            <div class="col-md-8">
                <div id="menu-list" class="row">
                    <?php
                    // Koneksi ke database
                    include 'config/config.php';

                    // Mengambil data menu dari database
                    $sql = "SELECT * FROM menu WHERE menu_status = 'available'";
                    $result = $conn->query($sql);

                    // Menampilkan setiap item menu dalam format HTML
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                                <div class="col-md-3 mb-3 d-flex justify-content-center">
                                    <div class="shadow card card-hover text-center" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                                        <img src="' . $row["menu_photo"] . '" class="card-img-top" alt="' . $row["menu_name"] . '" style="height: 200px; object-fit: cover;">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <h5 class="card-title">' . $row["menu_name"] . '</h5>
                                            <p class="card-text">' . $row["menu_desc"] . '</p>
                                            <p class="card-text">Harga: <strong>RP. ' . $row["menu_price"] . '</strong></p>
                                            <button onclick="addToCart(\'' . $row["menu_id"] . '\', \'' . $row["menu_name"] . '\', ' . $row["menu_price"] . ')" class="btn btn-primary mt-auto">Tambahkan</button>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }                        
                    } else {
                        echo '<p class="text-center">Tidak ada menu tersedia.</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Bagian keranjang dan pemilihan meja sticky di sebelah kanan -->
            <div class="col-md-4">
                <div id="cart-section">
                    <h2>Keranjang</h2>
                    <ul id="cart-list" class="list-group mb-3"></ul>
                    <div id="total-price" class="mb-3">Total Harga: RP. 0</div>
                    <button id="checkout-button" class="btn btn-success w-100">Checkout</button>
                    <div class="mt-4">
                        <input type="text" class="form-control form-control-lg" placeholder="Nama Pemesan" name="name" id="name" required>
                    </div>
                    <div id="table-selection" class="mt-4">
                        <h3>Pilih Meja</h3>
                        <select id="table-dropdown" class="form-select">
                            <?php
                             $sql = "SELECT * FROM tables";
                             $result = $conn->query($sql);

                             if ($result->num_rows > 0) {
                                 while ($table = $result->fetch_assoc()) {
                                     echo '<option value="' . $table["table_id"] . '">' . $table["table_number"] . '</option>';
                                 }
                             }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(menu_id, menu_name, harga) {
            const item = cart.find(i => i.menu_id === menu_id);
            if (item) {
                item.quantity++;
            } else {
                cart.push({ menu_id, menu_name, harga, quantity: 1 });
            }
            renderCart();
        }

        function renderCart() {
            $('#cart-list').empty();
            cart.forEach(item => {
                $('#cart-list').append(`<li class="list-group-item d-flex justify-content-between align-items-center">${item.menu_name} - ${item.quantity} x ${item.harga} <button onclick="removeFromCart('${item.menu_id}')" class="btn btn-danger btn-sm">Hapus</button></li>`);
            });
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            cart.forEach(item => {
                total += item.harga * item.quantity;
            });
            $('#total-price').text(`Total Harga: RP. ${total}`);
        }

        function removeFromCart(menu_id) {
            cart = cart.filter(item => item.menu_id !== menu_id);
            renderCart();
        }

        $('#checkout-button').on('click', function() {
            if (cart.length === 0) {
                alert("Keranjang kosong!");
                return;
            }

            const table_id = $('#table-dropdown').val();
            const name = $('#name').val();
            $.ajax({
                url: 'backend/checkout.php',
                method: 'POST',
                data: {
                    cart: JSON.stringify(cart),
                    table_id: table_id,
                    name: name
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    if (response.message === 'Checkout berhasil!') {
                        cart = [];
                        renderCart();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat proses checkout.');
                }
            });
        });
    </script>
</body>
</html>
