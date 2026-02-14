<?php 
require_once __DIR__.'/../model/Panne.php';
$panne=new Panne();
$idPersonne =$_SESSION['personne']['idP'];

$pannes=$panne->getdemandesInterventions($idPersonne);
