<?php 
session_start();
require_once __DIR__.'/../model/persoone.php';
$personne=new Persoone();
if(isset($_GET['id'])){
    $employe=$personne->getPersonneInfos($_GET['id']);
        $_SESSION['successac']="le compte de ".$employe['prenom']." ".$employe['nom']." été sactivé avec success";
    $personne->activerEmploye($_GET['id']);
    header('Location: ../view/responsable/employe.php');
    exit();
}