<?php 

require_once __DIR__.'/../model/Intervention.php';
require_once __DIR__.'/../model/Panne.php';


if(isset($_POST['idDemande'], $_POST['idTechnicien'])){

    $model = new Intervention();
    $panne=new Panne();
    $model->affecterDemande($_POST['idDemande'], $_POST['idTechnicien']);
    $panne->modifierEtatDemande($_POST['idDemande'],'affectée');
    header("Location: ../view/responsable/index.php");
    exit();
}
