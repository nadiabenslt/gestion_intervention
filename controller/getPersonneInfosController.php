<?php 

require_once __DIR__.'/../model/persoone.php';
$personne=new Persoone();
$personneInfos=$personne->getPersonneInfos($_SESSION['personne']['idP']);
