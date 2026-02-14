<?php 

require_once __DIR__.'/../model/Materiel.php';

$materiel=new Materiel();
$materiels=$materiel->getMateriels();
