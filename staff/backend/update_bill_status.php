<?php
header('Content-Type: application/json');
include('../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bill_id = isset($_POST['bill_id']) ? $_POST['bill_id'] : '';
    $bill_status = isset($_POST['bill_status']) ? $_POST['bill_status'] : '';

    if (empty($bill_id) || empty($bill_status)) {
        echo json_encode(['message' => 'ID Bill dan Status tidak boleh kosong.']);
        exit;
    }

    $sql = "UPDATE bill SET bill_status = ? WHERE bill_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo json_encode(['message' => 'Gagal mempersiapkan statement.']);
        exit;
    }

    $stmt->bind_param('ss', $bill_status, $bill_id);
    
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Status bill berhasil diperbarui.']);
    } else {
        echo json_encode(['message' => 'Gagal memperbarui status bill: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['message' => 'Metode tidak valid.']);
}

$conn->close();
?>
