<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sertakan file TCPDF
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');
include '../../config/config.php';

// Ambil parameter yang dikirimkan
$billId = $_GET['bill_id'];
$amountGiven = $_GET['amount_given'];
$change = $_GET['change'];

// Ambil data bill dan items
$billData = getBillDetails($conn, $billId);
$billItems = getBillItems($conn, $billData['bill_id']);

// Inisialisasi objek TCPDF
$pdf = new TCPDF();
$pdf->SetFont('helvetica', '', 12);

// Tambahkan halaman
$pdf->AddPage();

// Judul PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Invoice Pembelian', 0, 1, 'C');

// Tulis data bill
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 10, 'ID Bill: ' . $billData['bill_id'], 0, 1);
$pdf->Cell(40, 10, 'Nama: ' . $billData['bill_name'], 0, 1);

// Detail Barang yang Dibeli
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(40, 10, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(40, 10, 'Harga', 1, 0, 'C');
$pdf->Cell(40, 10, 'Jumlah', 1, 0, 'C');
$pdf->Cell(40, 10, 'Total', 1, 1, 'C');

// Tulis detail setiap item
$pdf->SetFont('helvetica', '', 12);
foreach ($billItems as $item) {
    $pdf->Cell(40, 10, $item['menu_name'], 1, 0, 'C');
    $pdf->Cell(40, 10, 'Rp. ' . number_format($item['price'], 2, ',', '.'), 1, 0, 'C');
    $pdf->Cell(40, 10, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, 'Rp. ' . number_format($item['price'] * $item['quantity'], 2, ',', '.'), 1, 1, 'C');
}

// Tulis total, pajak, dan total dengan pajak
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(40, 10, 'Total Pembelian', 1, 0, 'C');
$pdf->Cell(40, 10, 'Rp. ' . number_format($billData['bill_total'], 2, ',', '.'), 1, 1, 'C');

$pdf->Cell(40, 10, 'Pajak (10%)', 1, 0, 'C');
$pdf->Cell(40, 10, 'Rp. ' . number_format($tax, 2, ',', '.'), 1, 1, 'C');

$pdf->Cell(40, 10, 'Total dengan Pajak', 1, 0, 'C');
$pdf->Cell(40, 10, 'Rp. ' . number_format($totalWithTax, 2, ',', '.'), 1, 1, 'C');

// Tulis informasi pembayaran
$pdf->Cell(40, 10, 'Uang Diberikan', 1, 0, 'C');
$pdf->Cell(40, 10, 'Rp. ' . number_format($amountGiven, 2, ',', '.'), 1, 1, 'C');

$pdf->Cell(40, 10, 'Kembalian', 1, 0, 'C');
$pdf->Cell(40, 10, 'Rp. ' . number_format($change, 2, ',', '.'), 1, 1, 'C');

// Output PDF ke browser
$pdf->Output('I', 'Invoice_' . $billId . '.pdf');
?>
