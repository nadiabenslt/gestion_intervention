<?php
session_start();


require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);   // مهم للصور/الروابط
$options->set('isHtml5ParserEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../view'));



require_once __DIR__.'/../model/Panne.php';

$id = (int)($_GET['id']);
$panne = new Panne();
$panneData = $panne->getPanneById($id);

ob_start();
include __DIR__ . '/../view/fichePanne.php';
$html = ob_get_clean();

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("demande_panne_$id.pdf", ["Attachment" => false]);
exit;
