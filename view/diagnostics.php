<?php
require_once __DIR__ . '/../controller/diagnosticInterventionController.php';

$img1 = __DIR__ . '/images/logo-removebg-preview.png';
$img2 = __DIR__ . '/images/MATNUHPV-removebg-preview.png';
$img3 = __DIR__ . '/images/logoAUL.jpg';


$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Fiche Diagnostics</title>
  <link rel="icon" type="image/jpg" href="<?= $img3; ?>">

  <style>
    @page { margin: 14mm; }

    body{
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 13px;
      color: #111;
    }

    #ficheDiagnostics{ width: 100%; }

    .card-body{ padding: 0; }

    .text-center{ text-align: center; }
    .text-start{ text-align: left; }

    .mb-3{ margin-bottom: 14px; }
    .mb-4{ margin-bottom: 18px; }
    .mb-2{ margin-bottom: 8px; }

    .fw-bold{ font-weight: 700; }

    h2{
      font-size: 22px;
      margin: 8px 0 10px;
    }

    .d-inline-block{ display: inline-block; }

    hr{
      border: 0;
      border-top: 1px solid #111;
      margin: 12px 0;
    }

    table{
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    thead th{
      border: 1px solid #111;
      padding: 8px 10px;
      font-weight: 700;
      text-align: left;
      background: #f2f2f2;
    }

    tbody td{
      border: 1px solid #111;
      padding: 8px 10px;
      vertical-align: top;
    }

    .badge{
      display: inline-block;
      padding: 2px 8px;
      border: 1px solid #111;
      border-radius: 999px;
      font-size: 12px;
    }

    .muted{ color: #444; }

    img{ display: inline-block; }
  </style>
</head>
<body>

  <div id="ficheDiagnostics" class="container py-3">
    <div class="rounded-4">
      <div class="card-body p-4">

        <div class="text-center mb-3">
          <img src="<?= $img1 ?>" style="height:55px; margin-right:25px;" alt="logo1">
          <img src="<?= $img2 ?>" style="height:55px; margin-left:25px;"  alt="logo2">
        </div>

        <br><br>

        <div class="text-center mb-4">
          <h2 class="fw-bold mb-3">Fiche des diagnostics (interventions terminées)</h2>

          <div class="d-inline-block text-start">
            <div><span class="fw-bold">Total:</span> <?= count($interventions) ?></div>
          </div>
        </div>

        <div class="rounded-3 p-3">
          <h5 class="fw-bold mb-2">Liste des interventions terminées</h5>
          <div class="muted">Les diagnostics ci-dessous correspondent aux interventions dont l'état est <span class="badge">terminée</span>.</div>

          <hr class="border-dark">

          <table>
            <thead>
              <tr>
                <th style="width: 9%">ID</th>
                
                <th style="width: 18%">Action</th>
                <th style="width: 15%">Type</th>
                <th style="width: 14%">Date début</th>
                <th style="width: 14%">Date fin</th>
                <th style="width: 14%">Technicien</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($interventions)): ?>
                <tr>
                  <td colspan="6" class="text-center">Aucune intervention terminée.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($interventions as $i): ?>
                  <tr>
                    <td><?= htmlspecialchars($i['idIntervention'] ?? '') ?></td>
                    <td><?= htmlspecialchars($i['action'] ?? '') ?></td>
                    <td><?= htmlspecialchars($i['typeIntervention'] ?? '') ?></td>
                    <td><?= htmlspecialchars($i['dateDebut'] ?? '') ?></td>
                    <td><?= htmlspecialchars($i['dateFin'] ?? '') ?></td>
                    <td>
                      <?= htmlspecialchars(($i['prenom'] ?? '') . ' ' . ($i['nom'] ?? '')) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

        </div>

      </div>
    </div>
  </div>

</body>
</html>
