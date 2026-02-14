<?php 

require_once __DIR__.'/../model/persoone.php';
$personne=new Persoone();
if(isset($_GET['id'])){
    $_SESSION['successde']='le compte de ce client été desactivé avec success';
    $personne->desactiverEmploye($_GET['id']);

    header('Location: ../view/responsable/employe.php');
    exit();
}elseif(isset($_GET['idD'])){
    $personne->activerEmploye($_GET['id']);

    header('Location: ../view/responsable/employe.php');
    exit();
}