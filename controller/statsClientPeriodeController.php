<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../model/Intervention.php';

$dateDebut = $_GET['start'] ?? null;
$dateFin   = $_GET['end'] ?? null;

if (!$dateDebut || !$dateFin) {
  $dateFin = date('Y-m-d');
  $dateDebut = date('Y-m-d', strtotime('-30 days'));
}

$intervention = new Intervention();
$rows = $intervention->statsParClientPeriode($dateDebut . " 00:00:00", $dateFin . " 23:59:59");

echo json_encode([
  'labels' => array_column($rows, 'label'),
  'values' => array_map('intval', array_column($rows, 'total')),
], JSON_UNESCAPED_UNICODE);
