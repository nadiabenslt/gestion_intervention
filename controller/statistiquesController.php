<?php


require_once __DIR__ . '/../model/Panne.php';
require_once __DIR__ . '/../model/Departement.php';
require_once __DIR__ . '/../model/persoone.php';


$dash = new Panne();
$dep=new Departement();
$personne=new Persoone();
$kpi = $dash->getKpis();

$kpi['arisque'] = $kpi['arisque'] ?? 0;

$deps  = $dep->getDepartements();
$techs = $personne->getTechniciens();

$priorites = $dash->getPriorites(15);

$demandes = $dash->searchDemandes($_GET);

$statsDays = $dash->statsParJour(7);

