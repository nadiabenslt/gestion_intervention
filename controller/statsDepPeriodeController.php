<?php
require_once __DIR__.'/../model/Panne.php';
header('Content-Type: application/json');

$start = $_GET['start'] ?? date('Y-m-d', strtotime('-7 days'));
$end   = $_GET['end']   ?? date('Y-m-d');

$stats = new Panne();
$data = $stats->statsParDepartement($start, $end);

echo json_encode([
    'labels' => array_column($data, 'dep'),
    'values' => array_column($data, 'total')
]);