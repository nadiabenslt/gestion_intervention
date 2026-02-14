<?php

require_once __DIR__ . '/../model/persoone.php';

$userModel = new Persoone();

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$active = isset($_GET['active']) ? trim($_GET['active']) : 'all';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 9;
if (!in_array($perPage, [6,9,12,18], true)) $perPage = 9;

$total = $userModel->countEmployes($q, $active);
$totalPages = max(1, (int)ceil($total / $perPage));

if ($page > $totalPages) $page = $totalPages;

$offset = ($page - 1) * $perPage;

$employes = $userModel->getEmployesPaginated($q, $active, $perPage, $offset);

$pagination = [
  'total' => $total,
  'page' => $page,
  'perPage' => $perPage,
  'totalPages' => $totalPages,
  'q' => $q,
  'active' => $active,
];
