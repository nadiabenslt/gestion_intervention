<?php 

require_once __DIR__.'/../model/Intervention.php';
require_once __DIR__.'/../model/Panne.php';


if(isset($_GET['id']) && isset($_GET['idD']) ){
    $intervention=new Intervention();
    $panne=new Panne();

    $intervention->commencerIntervention($_GET['id']);
    $panne->modifierEtatDemande($_GET['idD'],'en cours');
    header("Location: ../view/technicien/index.php");
    exit();
}