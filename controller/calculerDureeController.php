<?php 
require_once __DIR__.'/../model/Panne.php';
$demande=new Panne();
$pannes = $demande->calculerDuree();
