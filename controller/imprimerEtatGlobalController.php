<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../model/Panne.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$etatKey = $_GET['etat'] ?? 'all';
$panneModel = new Panne();
$toutesLesPannes = $panneModel->calculerDuree();

$pannesFiltrees = [];
if ($etatKey === 'retard') {
    $pannesFiltrees = array_filter($toutesLesPannes, function($p) {
        return (int)$p['isOverdue'] === 1;
    });
} elseif ($etatKey !== 'all') {
    $mapping = ['affecter' => 'affecter', 'en cours' => 'en cours', 'cloturée' => 'terminée'];
    $etatRecherche = $mapping[$etatKey] ?? $etatKey;
    $pannesFiltrees = array_filter($toutesLesPannes, function($p) use ($etatRecherche) {
        return trim($p['etatDemande']) === $etatRecherche;
    });
} else {
    $pannesFiltrees = $toutesLesPannes;
}

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

ob_start();
include __DIR__.'/../view/print_template.php';
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); 
$dompdf->render();

$dompdf->stream("Rapport_" . $etatKey . ".pdf", ["Attachment" => false]);