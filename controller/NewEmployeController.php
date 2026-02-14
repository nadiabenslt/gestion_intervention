<?php 
session_start();
require_once __DIR__.'/../model/persoone.php';

$personne=new Persoone();
if (isset($_POST['ajouter'])){
    $_SESSION['ajouterEmploye']='employé ajouter avec success';
    $personne->ajouterPersonne($_POST);
    header('Location: ../view/responsable/employe.php');
    exit();
}