<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../model/Materiel.php';

$materiel = new Materiel();

/**
 * POST: update materiel
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['idMateriel'] ?? 0);

    if ($id > 0) {
        $materiel->modifierMateriel($id, $_POST);
        $_SESSION['success'] = 'Matériel modifié avec succès';
    }

    header('Location: ../view/responsable/materiels.php');
    exit();
}

$materiels = $materiel->getMateriels();

$materielData = null;

$idEdit = 0;
if (isset($_GET['edit'])) $idEdit = (int)$_GET['edit'];
elseif (isset($_GET['id'])) $idEdit = (int)$_GET['id'];

if ($idEdit > 0) {
    $materielData = $materiel->getMaterielById($idEdit);
}
