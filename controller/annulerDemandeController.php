<?php 

require_once __DIR__.'/../model/Panne.php';
$panne=new Panne();

if($_SERVER['REQUEST_METHOD']=='GET'){
    $panne->annulerDemande($_GET['id']);
    header('Location: ../view/employe/index.php');
    exit();
}

