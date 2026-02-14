<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../model/Intervention.php';

$dateDebut = $_GET['start'] ?? null;
$dateFin   = $_GET['end'] ?? null;

// default: آخر 30 يوم
if (!$dateDebut || !$dateFin) {
  $dateFin = date('Y-m-d');
  $dateDebut = date('Y-m-d', strtotime('-30 days'));
}

$intervention = new Intervention();
$data = $intervention->statsParEquipementEtType($dateDebut . " 00:00:00", $dateFin . " 23:59:59");

$equip = $data['equip'] ?? [];
$types = $data['types'] ?? [];

echo json_encode([
  'equipLabels' => array_column($equip, 'label'),
  'equipValues' => array_map('intval', array_column($equip, 'total')),
  'typeLabels'  => array_column($types, 'label'),
  'typeValues'  => array_map('intval', array_column($types, 'total')),
], JSON_UNESCAPED_UNICODE);
