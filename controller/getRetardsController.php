<?php
require_once __DIR__.'/../model/Panne.php';
header('Content-Type: application/json');

$model = new Panne();
$retards = $model->getDemandesEnRetard();

echo json_encode($retards);
exit;
