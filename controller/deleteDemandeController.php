<?php 
session_start();
require_once __DIR__.'/../model/Panne.php';
$panne=new Panne();

if($_SERVER['REQUEST_METHOD']=='GET'){
    $panne->supprimerDemande($_GET['id']);
    if($_SESSION['personne']['role']=='employe'){
        header('Location: ../view/employe/index.php');
        exit();
    }elseif ($_SESSION['personne']['role']=='responsable'){
        header('Location: ../view/responsable/DemanderIntervention.php');
        exit();
    }
}
