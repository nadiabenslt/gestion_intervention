<?php
require_once __DIR__.'/../model/Departement.php';

$departement = new Departement();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idDep = (int)$_POST['idDep'];
    $nom = trim($_POST['nom']);
    $numEtage = (int)$_POST['numEtage'];

    $departement->modifierDepartement($idDep, $nom, $numEtage);

    header('Location: ../view/responsable/departements.php');
    exit();
}
