<?php
session_start();

if (
  !isset($_SESSION['personne']) ||
  $_SESSION['personne']['role'] !== 'responsable'
) {
  header('Location: ../index.php');
  exit;
}
require_once __DIR__ . '/../../controller/typesInterventionsController.php';

require_once __DIR__ . '/../../controller/getInterventionsController.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Interface Technicien</title>
<link href="../styles/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.priority-urgent{border-left:6px solid #dc3545;}
.priority-moyen{border-left:6px solid #fd7e14;}
.priority-normal{border-left:6px solid #0d6efd;}

@keyframes blink {50%{opacity:.3}}
.blink{animation:blink 1s infinite;}
</style>
</head>

<body class="bg-light">


<div class="container-fluid">
  <div class="row">

    
    <main class="col-12 col-md-9 col-lg-12">

      <div class="bg-white border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3">
          <div>
            <div class="text-muted small">Interface — Technicien</div>
            <h5 class="mb-0">
              Bonjour
              <span class="fw-semibold">
                <?= htmlspecialchars($_SESSION['personne']['prenom'].' '.$_SESSION['personne']['nom']) ?>
              </span>
            </h5>
          </div>

          <div class="d-flex gap-2">
            <a href="../responsable/index.php" class="btn btn-outline-info btn-sm">🔁 Responsable</a>
            <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
          </div>
        </div>
      </div>


      <div class="p-3 p-lg-4">

        <ul class="nav nav-tabs mb-3" id="tabs">
          <li class="nav-item"><button class="nav-link active" data-tab="encours">En cours</button></li>
          <li class="nav-item"><button class="nav-link" data-tab="todo">À faire</button></li>
          <li class="nav-item"><button class="nav-link" data-tab="done">Clôturées</button></li>
        </ul>

        <?php foreach($interventions as $row):

          $etat = trim($row['etatDemande']);
          $h = (int)$row['delaiHeures'];

          $priority='priority-normal';
          if(($row['isOverdue'] ?? 0)==1 || $h>=120) $priority='priority-urgent';
          elseif($h>=72) $priority='priority-moyen';

          $tab='todo';
          if($etat=='en cours') $tab='encours';
          if($etat=='terminée') $tab='done';
        ?>

        <div class="card shadow-sm mb-3 tech-card <?= $priority ?>" data-tab="<?= $tab ?>">
          <div class="card-body">

            <div class="d-flex justify-content-between">
              <strong>#<?= $row['idDemandeIn'] ?></strong>
              <span class="badge bg-info"><?= htmlspecialchars($etat) ?></span>
            </div>

            <div class="mt-2">
              <strong><?= htmlspecialchars($row['typeM'].$row['marqueM']) ?></strong>
              <div class="text-muted small"><?= htmlspecialchars($row['lieuMateriel']) ?></div>
            </div>

            <div class="small mt-2">
              <?= htmlspecialchars($row['description']) ?>
            </div>

            <div class="mt-2">
              <?php if($h>=120): ?>
                <span class="badge bg-danger blink">⏱ <?= $h ?>h</span>
              <?php elseif($h>=96): ?>
                <span class="badge bg-warning">⏱ <?= $h ?>h</span>
              <?php else: ?>
                <span class="badge bg-success">⏱ <?= $h ?>h</span>
              <?php endif; ?>
            </div>

            <div class="mt-3 d-flex gap-2">

              <?php if($etat=='affectée' || $etat=='affecter'): ?>
                <a class="btn btn-sm btn-primary"
           href="/backend/controller/commencerInterventionController.php?id=<?= (int)$row['idIntervention'] ?>&idD=<?= (int)$row['idDemandeIn'] ?>">
          Démarer
        </a>
              <?php endif; ?>

              <?php if($etat=='en cours'): ?>
                <button class="btn btn-success btn-sm terminerBtn"
                  data-id="<?= (int)$row['idIntervention'] ?>"
                  data-demande="<?= (int)$row['idDemandeIn'] ?>"
                  data-bs-toggle="modal"
                  data-bs-target="#terminerModal">
                  Terminer
                </button>
              <?php endif; ?>

              <?php if($etat=='terminée' || $etat=='cloturée'): ?>
                <a class="btn btn-sm btn-outline-secondary"
                   href="/backend/controller/imprimerInterventionController.php?id=<?= (int)$row['idIntervention'] ?>&idD=<?= (int)$row['idDemandeIn'] ?>">
                  <i class="bi bi-printer"></i> Imprimer
                </a>
              <?php endif; ?>

            </div>

          </div>
        </div>

        <?php endforeach; ?>

      </div>
    </main>

  </div>
</div>

<div class="modal fade" id="terminerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="/backend/controller/terminerInterventionController.php">
      <div class="modal-header">
        <h5 class="modal-title">FICHE D'INTERVENTION</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="idIntervention" id="idInterventionInput">
        <input type="hidden" name="idDemandeIn" id="idDemandeIn">
         <div class="mb-2">
          <label class="form-label">date fin intervention</label>
          <input type="datetime-local" name="dateFin" class="form-control" required>
        </div>

        <div class="mb-2">
          <label class="form-label">type intervention</label>

<select name="typeIntervention" id="" class="form-control"  required>
  <option value="">-- selectionner type --</option>
  <?php foreach($typesIntervention as $ti): ?>
<option value="<?= $ti['typeIntervention'] ?>"><?= $ti['typeIntervention'] ?></option>
  <?php endforeach;?>
</select>        </div>

        <div class="mb-2">
            <label class="form-label">Action entreprise</label>
          <textarea name="action" class="form-control" rows="3" required></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" name="terminerIntervention" class="btn btn-success">Valider</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>
<script src="../styles/js/bootstrap.bundle.min.js"></script>

<script>
function applyTab(val){
  document.querySelectorAll('.tech-card').forEach(card=>{
    card.style.display = (card.dataset.tab === val) ? 'block' : 'none';
  });
}

const tabButtons = document.querySelectorAll('#tabs .nav-link');

tabButtons.forEach(btn=>{
  btn.addEventListener('click', ()=>{
    tabButtons.forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    applyTab(btn.dataset.tab);
  });
});

document.addEventListener('DOMContentLoaded', ()=>{
  const activeBtn = document.querySelector('#tabs .nav-link.active');
  const defaultTab = activeBtn ? activeBtn.dataset.tab : 'encours';
  applyTab(defaultTab);
});

document.querySelectorAll('.terminerBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('idInterventionInput').value = btn.dataset.id;
    document.getElementById('idDemandeIn').value = btn.dataset.demande; // ✅ هنا
  });
});

</script>

</body>
</html>
