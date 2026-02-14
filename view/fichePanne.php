<?php
$img1= __DIR__.'/images/logo-removebg-preview.png';
$img2= __DIR__.'/images/MATNUHPV-removebg-preview.png';
$img3= __DIR__.'/images/logoAUL.jpg';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard employe</title>
<link rel="icon" type="image/jpg" href="<?= $img3; ?>">

<style>
  @page { margin: 14mm; }

  body{
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 13px;
    color: #111;
  }

  #fichePanne{
    width: 100%;
  }



  .card-body{
    padding: 0;  
  }

  .d-flex{
    display: flex;
  }
  .justify-content-center{
    justify-content: center;
  }
  .align-items-center{
    align-items: center;
  }
  .gap-3{
    gap: 14px;       
  }
  .mb-3{ margin-bottom: 14px; }

  img{
    display: inline-block;
  }

  .text-center{
    text-align: center;
  }
  .mb-4{ margin-bottom: 18px; }

  h2{
    font-size: 22px;
    margin: 8px 0 10px;
  }

  .fw-bold{
    font-weight: 700;
  }

  .d-inline-block{
    display: inline-block;
  }
  .text-start{
    text-align: left;
  }



  .p-3{ padding: 14px; } 

  h5{
    font-size: 14px;
    margin: 0 0 10px;
  }

  .mb-2{ margin-bottom: 8px; }

  hr{
    border: 0;
    border-top: 1px solid #111;
    margin: 12px 0;
  }

  .description{
    margin-top: 6px;
    white-space: pre-wrap;      
    word-wrap: break-word;
    
  }
  img[alt='AULSH']{
    margin-left:5% ;
  }
  img[alt='Royaume']{
    margin-left:18% ;
  }
</style>

</head>

<body>

  <div id="fichePanne" class="container py-3">

    <div class=" rounded-4">
      <div class="card-body p-4 ml-5">

        <div class="text-center mb-3">
  <img src="<?= $img1 ?>" style="height:55px; margin-right:25px;" alt="logo1">
  <img src="<?= $img2 ?>" style="height:55px; margin-left:25px;"  alt="logo2">
</div>

<br><br>
        <div class="text-center mb-4">
          <h2 class="fw-bold mb-3">Fiche de demande d'intervention</h2>

          <div class="d-inline-block text-start">
            <div><span class="fw-bold">Réf:</span> <?= htmlspecialchars($panneData['idDemandeIn'] ?? '') ?></div>
            <div><span class="fw-bold">Date:</span> <?= htmlspecialchars($panneData['dateDemande'] ?? '') ?></div>
            <div><span class="fw-bold">État:</span> <?= htmlspecialchars($panneData['etatDemande'] ?? '') ?></div>
          </div>
        </div>

        <div class=" rounded-3 p-3">

          <h5 class="fw-bold mb-3">Demandeur:</h5>
          <div class="mb-2"><span class="fw-bold">Nom:</span> <?= htmlspecialchars(($panneData['prenom'] ?? '').' '.($panneData['nom'] ?? '')) ?></div>
          <div class="mb-2"><span class="fw-bold">Département:</span> <?= htmlspecialchars($panneData['dep'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Salle:</span> <?= htmlspecialchars($panneData['numSalle'] ?? '') ?></div>

          <hr class="border-dark">

          <h5 class="fw-bold mb-3">Matériel:</h5>
          <div class="mb-2"><span class="fw-bold">Numéro série:</span> <?= htmlspecialchars($panneData['numero'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Type:</span> <?= htmlspecialchars($panneData['typeM'] ?? '') ?></div>
          <div class="mb-2"><span class="fw-bold">Marque:</span> <?= htmlspecialchars($panneData['marque'] ?? '') ?></div>

          <hr class="border-dark">

          <h5 class="fw-bold mb-2">Description du panne:</h5>
          <div><?= htmlspecialchars($panneData['description']) ?></div>

        </div>
      </div>
  

</body>
</html>