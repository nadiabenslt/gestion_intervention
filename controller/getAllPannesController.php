<?php 

require_once __DIR__.'/../model/Panne.php';

$panne=new Panne();
$pannes=$panne->getAlldemandesInterventions();


