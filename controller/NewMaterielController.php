<?php 

require_once __DIR__.'/../model/Materiel.php';

$materiel=new Materiel();
if (isset($_POST['ajouterMateriel'])){
    $materiel->ajouterMateriel($_POST);
    header('Location: ../view/responsable/materiels.php');
    exit();
}