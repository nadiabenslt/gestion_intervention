<?php 

require_once __DIR__.'/../model/Intervention.php';
$intervention=new Intervention();
$typesIntervention=$intervention->typesInterventions();