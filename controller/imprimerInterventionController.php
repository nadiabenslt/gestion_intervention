<?php 

session_start();
if (!isset($_SESSION['personne']['role']) || $_SESSION['personne']['role'] !== 'responsable') {
  header('Location: ../index.php'); exit;
}
if (!isset($_GET['id'], $_GET['idD'])) { die('Paramètres manquants'); }

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../model/Panne.php';
require_once __DIR__.'/../model/Intervention.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$idDemande = (int)$_GET['idD'];
$idIntervention = (int)$_GET['id'];

$panne = new Panne();
$panneData = $panne->getPanneById($idDemande);

$intervention = new Intervention();
$interventionData = $intervention->getInterventionById($idIntervention);
$fiche = [
  'idIntervention'   => $idIntervention,
  'dateDebut'        => $interventionData['dateDebut'] ?? '',
  'dateFin'          => $interventionData['dateFin'] ?? '',
  'nomDemandeur'     => ($panneData['prenom'] ?? '').' '.($panneData['nom'] ?? ''),
  'departement'      => $panneData['dep'] ?? '',
  'numSalle'         => $panneData['numSalle'] ?? '',
  'numeroSerie'      => $panneData['numero'] ?? '',
  'typeMateriel'     => $panneData['typeM'] ?? '',
  'marque'           => $panneData['marque'] ?? '',
  'dateDeclaration'  => $panneData['dateDemande'] ?? '',
  'description'      => $panneData['description'] ?? '',
  'nomTechnicien'    => ($interventionData['prenom'] ?? '').' '.($interventionData['nom'] ?? ''),
  'diagnostique'     => $interventionData['typeIntervention'] ?? '',
  'action'           => $interventionData['action'] ?? '',
];

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../view'));

ob_start();
include __DIR__.'/../view/technicien/ficheIntervention.php';
$html = ob_get_clean();

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("fiche_intervention_$idIntervention.pdf", ["Attachment"=>false]);
exit;
