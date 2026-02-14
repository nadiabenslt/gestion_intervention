<?php 

require_once __DIR__.'/../model/persoone.php';

$personne=new Persoone();
$techniciens=$personne->getTechniciens();