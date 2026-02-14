<?php 

require_once __DIR__.'/../model/Marque.php';

$marquesMateriels=new Marque();
$marques=$marquesMateriels->getMarques();


