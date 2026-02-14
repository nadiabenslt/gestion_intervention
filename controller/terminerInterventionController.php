<?php 

require_once __DIR__.'/../model/Intervention.php';
require_once __DIR__.'/../model/Panne.php';

$intervention=new Intervention();
$demande=new Panne();
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['idDemandeIn']) && isset($_POST['idIntervention'])){
        $data=['typeIntervention'=>$_POST['typeIntervention'], 'dateFin'=> $_POST['dateFin'], 'action'=> $_POST['action']];
        $intervention->remplirFicheIntervention($_POST['idIntervention'],$data);
        $demande->modifierEtatDemande($_POST['idDemandeIn'],'terminée');
        header('Location: ../view/technicien/index.php');
        exit();
    }
}