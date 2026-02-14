<?php 
session_start();
$idPersonne =$_SESSION['personne']['idP'];

require_once __DIR__.'/../model/Panne.php';
$panne=new Panne();


if(isset($_POST['declarer'])){
    $panne->declarerDemande($idPersonne,$_POST);
    header('Location: ../view/employe/index.php');
    exit();
}
$today = date('Y-m-d'); 
if($_SERVER['REQUEST_METHOD']=='GET'){
    $panneWithId=$panne->getPanneById($_GET['id']);
    if ($panneWithId['etatDemande']=='annulée'){
        $panne->redeclarerDemande($_GET['id']);
        $_SESSION['success']='re-déclaration bien réussi';
    }
    if($_SESSION['personne']['role']=='employe'){
        header('Location: ../view/employe/index.php');
        exit();
    }elseif ($_SESSION['personne']['role']=='responsable'){
        header('Location: ../view/responsable/DemanderIntervention.php');
        exit();
    }
}