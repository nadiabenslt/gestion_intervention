<?php
$img1 = __DIR__.'/../images/logo-removebg-preview.png';
$img2 = __DIR__.'/../images/MATNUHPV-removebg-preview.png';
$img3= __DIR__.'/backend/view/images/logoAUL.jpg';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fiche intervention</title>
<link rel="icon" type="image/jpg" href="<?= $img3; ?>">

<style>
  @page { margin: 14mm; }

  body{
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 13px;
    color: #111;
  }

  #ficheIntervention{ width: 100%; }

  .card-body{ padding: 0; }

  /* logos line */
  .d-flex{ display: flex; }
  .justify-content-center{ justify-content: center; }
  .align-items-center{ align-items: center; }
  .gap-3{ gap: 14px; }
  .mb-3{ margin-bottom: 14px; }

  img{ display: inline-block; }

  /* title + meta center */
  .text-center{ text-align: center; }
  .mb-4{ margin-bottom: 18px; }

  h2{ font-size: 22px; margin: 8px 0 10px; }
  .fw-bold{ font-weight: 700; }

  .d-inline-block{ display: inline-block; }
  .text-start{ text-align: left; }

  .p-3{ padding: 14px; }
  h5{ font-size: 14px; margin: 0 0 10px; }
  .mb-2{ margin-bottom: 8px; }

  hr{
    border: 0;
    border-top: 1px solid #111;
    margin: 12px 0;
  }

  .sign{
    width:100%;
    border-collapse: collapse;
    margin-top: 22px;
  }
  .sign th, .sign td{
    border: 1px solid #111;
    padding: 8px;
    text-align: center;
  }
</style>
</head>

<body>
  <div id="ficheIntervention" class="container py-3">
    <div class="rounded-4">
      <div class="card-body p-4">

        <!-- ✅ نفس logos بحال fiche panne -->
        <div class="text-center mb-3">
  <img src="<?= $img1 ?>" style="height:55px; margin-right:25px;" alt="logo1">
  <img src="<?= $img2 ?>" style="height:55px; margin-left:25px;"  alt="logo2">
</div>


        <!-- ✅ بلا <hr> هنا باش مايبقاش ligne بين logos و fiche -->
        <div class="text-center mb-4">
          <h2 class="fw-bold mb-3">Fiche d’intervention</h2>

          <div class="d-inline-block text-start">
            <div><span class="fw-bold">Réf intervention:</span> <?= htmlspecialchars($fiche['idIntervention'] ?? '') ?></div>
            <div><span class="fw-bold">Date d’intervention:</span> <?= htmlspecialchars($fiche['dateDebut'] ?? '') ?></div>
          </div>
        </div>

        <div class="rounded-3 p-3">

          <h5 class="fw-bold mb-3">Demandeur:</h5>
          <div class="mb-2"><span class="fw-bold">Nom &amp; Prénom:</span> <?= htmlspecialchars($fiche['nomDemandeur'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Département:</span> <?= htmlspecialchars($fiche['departement'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Salle:</span> <?= htmlspecialchars($fiche['numSalle'] ?? '') ?></div>

          <hr>

          <h5 class="fw-bold mb-3">Matériel:</h5>
          <div class="mb-2"><span class="fw-bold">Numéro série:</span> <?= htmlspecialchars($fiche['numeroSerie'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Type:</span> <?= htmlspecialchars($fiche['typeMateriel'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Marque:</span> <?= htmlspecialchars($fiche['marque'] ?? '') ?></div>

          <hr>

          <h5 class="fw-bold mb-3">Demande d'intervention:</h5>
          <div class="mb-2"><span class="fw-bold">Date de déclaration:</span> <?= htmlspecialchars($fiche['dateDeclaration'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Description:</span> <?= htmlspecialchars($fiche['description'] ?? '') ?></div>

          <hr>

          <h5 class="fw-bold mb-3">Intervention:</h5>
          <div class="mb-2"><span class="fw-bold">Intervenant:</span> <?= htmlspecialchars($fiche['nomTechnicien'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">type intervention:</span> <?= htmlspecialchars($fiche['typeIntervention'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Action Entreprise:</span> <?= htmlspecialchars($fiche['action'] ?? '') ?></div>

          <table class="sign">
            <tr>
              <th>Demandeur</th>
              <th>Intervenant</th>
              <th>Département</th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($fiche['nomDemandeur'] ?? '') ?></td>
                <td><?= htmlspecialchars($fiche['nomTechnicien'] ?? '') ?></td>
                <td><?= htmlspecialchars($fiche['departement'] ?? '') ?></td>
            </tr>
            <tr>
              <td style="height:60px;"></td>
              <td></td>
              <td></td>
            </tr>
          </table>

        </div>

      </div>
    </div>
  </div>
</body>
</html>
