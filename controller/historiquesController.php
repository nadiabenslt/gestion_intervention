<?php 

require_once __DIR__.'/../model/Intervention.php';
$panne=new Intervention();
$historique=$panne->historiques();