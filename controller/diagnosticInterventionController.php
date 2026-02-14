<?php


require_once __DIR__ . '/../model/Intervention.php';

$intervention = new Intervention();
$interventions=$intervention->getDiagnostics();
function badgeEtat(string $etat): string {
    $etat = mb_strtolower(trim($etat));
    if ($etat === 'terminée' || $etat === 'terminee') return 'text-bg-success';
    if ($etat === 'en cours') return 'text-bg-warning';
    if ($etat === 'annulée' || $etat === 'annulee') return 'text-bg-danger';
    return 'text-bg-secondary';}