<?php 
require_once __DIR__.'/../model/Panne.php';
session_start();
$idTechnicien=$_SESSION['personne']['idP'];
$demande=new Panne();
$statistiques=$demande->getEtatsDemandes($idTechnicien);
$labels = [];
$values = [];

foreach ($statistiques as $row) {
    $labels[] = $row['etatDemande'];
    $values[] = $row['total'];
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode(['labels' => $labels, 'values' => $values]);
exit();