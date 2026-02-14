<?php 

require_once __DIR__.'/../model/Departement.php';
$departement=new Departement();
if(isset($_GET['id'])){
    $departement->supprimerDepartement($_GET['id']);
    header('Location: ../view/responsable/departements.php');
    exit();
}