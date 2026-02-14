<?php 

require_once __DIR__.'/../model/Materiel.php';

$materiel=new Materiel();
$result=$materiel->supprimerMateriel($_GET['id']);
header('Location: ../view/responsable/materiels.php');