<?php 

require_once __DIR__.'/../model/Intervention.php';
$idTechnicien=$_SESSION['personne']['idP'];
$intervention=new Intervention();
$interventions=$intervention->getInterventions($idTechnicien);