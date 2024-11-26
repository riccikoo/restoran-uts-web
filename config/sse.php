<?php
include '../config/config.php';
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$query = "SELECT * FROM bill ORDER BY created_at DESC";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo "data: " . json_encode($data) . "\n\n";
flush();
?>
