<?php 
require_once __DIR__.'/../model/Affectation.php';
require_once __DIR__.'/../model/Materiel.php';

$affectation = new Affectation();
$materiel    = new Materiel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'] ?? null;

    if (!$id) {
        header('Location: ../view/responsable/materiels.php');
        exit();
    }

    $materielData = $materiel->getMaterielById($id);

    if (!$materielData) {
        header('Location: ../view/responsable/materiels.php');
        exit();
    }

    include __DIR__.'/../view/responsable/affectation.php';
    exit();

} else {
    if (isset($_POST['affecterMateriel'])) {
        $affectation->affecterMateriel($_POST);
        header('Location: ../view/responsable/materiels.php');
        exit();
    }
}
