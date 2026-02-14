<?php 

require_once __DIR__.'/../model/Departement.php';

$departement=new Departement();
$departements=$departement->getdepartements();
