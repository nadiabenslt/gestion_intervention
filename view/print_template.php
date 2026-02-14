<?php

$img1 = __DIR__ . '/images/logo-removebg-preview.png';
$img2 = __DIR__ . '/images/MATNUHPV-removebg-preview.png';
$img3 = __DIR__ . '/images/logoAUL.jpg';


$etatLabel = $etatKey ?? 'all';
$generatedAt = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport des demandes</title>
<link rel="icon" type="image/jpg" href="<?= $img3; ?>">

<style>
  @page { margin: 14mm; }

  body{
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 13px;
    color: #111;
  }

  .text-center{ text-align:center; }
  .text-start{ text-align:left; }
  .fw-bold{ font-weight:700; }
  .mb-3{ margin-bottom:14px; }
  .mb-4{ margin-bottom:18px; }
  .d-inline-block{ display:inline-block; }

  .header-logos{
    text-align:center;
    margin-bottom: 14px;
  }
  .header-logos img{
    display:inline-block;
    height:55px;
  }

  h2{
    font-size: 22px;
    margin: 8px 0 10px;
  }

  hr{
    border:0;
    border-top:1px solid #111;
    margin:12px 0;
  }

  table{
    width:100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  thead th{
    background:#f2f2f2;
    border:1px solid #111;
    padding:8px;
    font-size: 11px;
    text-transform: uppercase;
    white-space: nowrap;
  }
  tbody td{
    border:1px solid #ccc;
    padding:8px;
    vertical-align: top;
    font-size: 12px;
  }

  .description{
    white-space: pre-wrap;
    word-wrap: break-word;
  }

  .badge{
    display:inline-block;
    padding:3px 7px;
    border-radius:4px;
    border:1px solid #111;
    font-size: 11px;
    font-weight:700;
  }
  .b-danger{ background:#dc3545; color:#fff; border-color:#dc3545; }
  .b-warning{ background:#ffc107; color:#111; border-color:#ffc107; }
  .b-success{ background:#198754; color:#fff; border-color:#198754; }

  footer{
    position: fixed;
    bottom: -10px;
    font-size: 10px;
    text-align: right;
    width: 100%;
    color: #555;
  }
</style>
</head>

<body>

  <div class="header-logos">
    <img src="data:image/png;base64,<?= base64_encode(@file_get_contents($img1) ?: '') ?>" alt="logo1" style="margin-right:25px;">
    <img src="data:image/png;base64,<?= base64_encode(@file_get_contents($img2) ?: '') ?>" alt="logo2" style="margin-left:25px;">
  </div>

  <div class="text-center mb-4">
    <h2 class="fw-bold mb-3">Rapport des demandes d'intervention</h2>

    <div class="d-inline-block text-start">
      <div><span class="fw-bold">État:</span> <?= htmlspecialchars($etatLabel) ?></div>
      <div><span class="fw-bold">Généré le:</span> <?= htmlspecialchars($generatedAt) ?></div>
      <div><span class="fw-bold">Total:</span> <?= isset($pannesFiltrees) && is_array($pannesFiltrees) ? count($pannesFiltrees) : 0 ?></div>
    </div>
  </div>

  <hr>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Matériel</th>
        <th>Lieu</th>
        <th>Description</th>
        <th>État</th>
        <th>SLA</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($pannesFiltrees) && is_array($pannesFiltrees)): ?>
        <?php foreach ($pannesFiltrees as $p):
          $h = isset($p['delaiHeures']) ? (int)$p['delaiHeures'] : 0;
          $isOver = isset($p['isOverdue']) ? ((int)$p['isOverdue'] === 1) : false;

          $materiel = trim(($p['libelleTypeMateriel'] ?? '') . ' ' . ($p['libelleMarque'] ?? ''));
          $etat = $p['etatDemande'] ?? '';
        ?>
          <tr>
            <td class="fw-bold">#<?= htmlspecialchars($p['idDemandeIn'] ?? '') ?></td>
            <td><?= htmlspecialchars($p['dateDemande'] ?? '') ?></td>
            <td><?= htmlspecialchars($materiel) ?></td>
            <td><?= htmlspecialchars($p['lieuMateriel'] ?? '') ?></td>
            <td class="description"><?= nl2br(htmlspecialchars($p['description'] ?? '')) ?></td>
            <td><?= htmlspecialchars($etat) ?></td>
            <td>
              <?php if ($isOver): ?>
                <span class="badge"><?= $h ?>h</span>
              <?php elseif ($h >= 96): ?>
                <span class="badge"><?= $h ?>h</span>
              <?php else: ?>
                <span class="badge"><?= $h ?>h</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">Aucune demande trouvée pour cet état.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
