<?php
require_once __DIR__.'/../model/Salle.php';

header('Content-Type: application/json; charset=utf-8');

$idDepartement = $_GET['idDepartement'] ?? $_GET['id'];

if (!$idDepartement) {
    echo json_encode([]);
    exit;
}

$salle = new Salle();
$salles = $salle->getSallesEmploye($idDepartement);

echo json_encode($salles);
exit;
