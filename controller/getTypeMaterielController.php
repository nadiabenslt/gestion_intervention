<?php 

require_once __DIR__.'/../model/TypeMateriel.php';

$typesMateriels=new TypeMateriel();
$types=$typesMateriels->getTypeMateriel();
