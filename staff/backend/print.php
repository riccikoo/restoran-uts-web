<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include '../../config/config.php';
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');

// Ambil data dari parameter URL
$billId = $_GET['bill_id'] ?? null;
$amountGiven = $_GET['amount_given'] ?? null;
$change = $_GET['change'] ?? null;

if (!$billId || !$amountGiven || !$change) {
    echo "Data tidak lengkap untuk mencetak PDF.";
    exit();
}

// Function untuk mendapatkan detail bill
function getBillDetails($conn, $billId) {
    $query = "SELECT * FROM bill WHERE bill_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $billId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function untuk mendapatkan detail item
function getBillItems($conn, $billId) {
    $query = "SELECT bd.quantity, bd.price, m.menu_name 
              FROM bill_details bd 
              JOIN menu m ON bd.menu_id = m.menu_id 
              WHERE bd.bill_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $billId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Ambil data bill dan items
$billData = getBillDetails($conn, $billId);
$billItems = getBillItems($conn, $billId);

if (!$billData) {
    echo "Bill tidak ditemukan.";
    exit();
}

// Hitung total dengan pajak
$total = $billData['bill_total'];
$tax = 0.10 * $total;
$totalWithTax = $total + $tax;

// Inisialisasi TCPDF
$pdf = new TCPDF();
$pdf->SetFont('helvetica', '', 12);

// Set margin untuk halaman
$pdf->SetMargins(10, 10, 10);  // Kiri, Atas, Kanan

// Aktifkan Auto Page Break
$pdf->SetAutoPageBreak(TRUE, 15);  // 15 adalah jarak margin bawah

// Tambahkan halaman
$pdf->AddPage('P', array(120, 210));

// Header Invoice
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'CikooFood', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Alamat: Mapay Jalan Satapak No. 123, Cimahi', 0, 1, 'C');
$pdf->Cell(0, 10, 'Telepon: (021) 12345678', 0, 1, 'C');
$pdf->Cell(0, 10, 'Email: info@cikoofood.com', 0, 1, 'C');

// Tanggal Invoice
$pdf->Cell(0, 10, 'Tanggal: ' . date('d-m-Y'), 0, 1, 'R');

// Informasi Bill
$pdf->SetFont('helvetica', 'B', 12);

// Tulis ID Bill dan Nama Pembeli
$pdf->Cell(50, 8, 'ID Bill: ' . $billData['bill_id'], 0, 1);  // Mengurangi tinggi baris
$pdf->Cell(50, 8, 'Nama Pembeli: ' . $billData['bill_name'], 0, 1);
$pdf->Ln(3); // Mengurangi jarak antar baris

// Tulis detail setiap item
$pdf->SetFont('helvetica', '', 11);
foreach ($billItems as $item) {
    $pdf->Cell(60, 6, $item['menu_name'], 0, 1, 'L');  // Lebar lebih lebar untuk nama menu
    $pdf->Cell(30, 8, 'Rp. ' . number_format($item['price'], 2, ',', '.'), 0, 0, 'L');  // Harga
    $pdf->Cell(5, 8, 'x', 0, 0, 'C');  // Tanda 'x'
    $pdf->Cell(20, 8, $item['quantity'], 0, 0, 'C');  // Jumlah
    $pdf->Cell(45, 8, 'Rp. ' . number_format($item['price'] * $item['quantity'], 2, ',', '.'), 0, 1, 'R');  // Total per item
}
$pdf->Line(10, $pdf->GetY() + 3, 110, $pdf->GetY() + 3);

// Tulis total, pajak, dan total dengan pajak
$pdf->Ln(3);  // Mengurangi jarak antar baris
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(70, 8, 'Total Pembelian', 0, 0, 'L');  // Rata kiri
$pdf->Cell(30, 8, 'Rp. ' . number_format($total, 2, ',', '.'), 0, 1, 'R');  // Rata kanan

$pdf->Cell(70, 8, 'Pajak (10%)', 0, 0, 'L');  // Rata kiri
$pdf->Cell(30, 8, 'Rp. ' . number_format($tax, 2, ',', '.'), 0, 1, 'R');  // Rata kanan

$pdf->Cell(70, 8, 'Total dengan Pajak', 0, 0, 'L');  // Rata kiri
$pdf->Cell(30, 8, 'Rp. ' . number_format($totalWithTax, 2, ',', '.'), 0, 1, 'R');  // Rata kanan
$pdf->Line(10, $pdf->GetY() + 3, 110, $pdf->GetY() + 3);

// Tulis informasi pembayaran
$pdf->Ln(3);  // Mengurangi jarak antar baris
$pdf->Cell(70, 8, 'Uang Diberikan', 0, 0, 'L');  // Rata kiri
$pdf->Cell(30, 8, 'Rp. ' . number_format($amountGiven, 2, ',', '.'), 0, 1, 'R');  // Rata kanan

$pdf->Cell(70, 8, 'Kembalian', 0, 0, 'L');  // Rata kiri
$pdf->Cell(30, 8, 'Rp. ' . number_format($change, 2, ',', '.'), 0, 1, 'R');  // Rata kanan

// Update status bill menjadi "paid"
$updateQuery = "UPDATE bill SET bill_status = 'paid' WHERE bill_id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("s", $billId);
$updateStmt->execute();

// Output PDF ke browser
$pdf->Output('Invoice_' . $billId . '.pdf', 'I');
header("Location:../index.php");
?>
