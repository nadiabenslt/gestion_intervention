<?php 

require_once __DIR__.'/../model/Departement.php';
$departement=new Departement();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if($_POST){
        $departement->ajouterDepartement($_POST);
        header('Location: ../view/responsable/departements.php');
        exit();
    }

}