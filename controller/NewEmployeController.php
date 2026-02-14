<?php 

require_once __DIR__.'/../model/persoone.php';

$personne=new Persoone();
if (isset($_POST['ajouter'])){
    $personne->ajouterPersonne($_POST);
    header('Location: ../view/responsable/employe.php');
    exit();
}