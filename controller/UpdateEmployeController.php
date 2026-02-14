<?php 
session_start();
require_once __DIR__.'/../model/persoone.php';

$personne=new Persoone();
 if(empty($_GET['id'])) { die("ID man9oss"); }

  $ok = $personne->updateInfos((int)$_GET['id'], $_POST);
  $_SESSION['success'] = $ok ? 'employé modifié avec succès' : 'Erreur lors de la modification';

  header('Location: ../view/responsable/employe.php');
  exit();
