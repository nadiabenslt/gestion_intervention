<?php

require_once __DIR__ . '/../model/Materiel.php';

$idPersonne =$_SESSION['personne']['idP'];

$materiel = new Materiel();
$materiels = $materiel->getMaterielByPersonne($idPersonne);
