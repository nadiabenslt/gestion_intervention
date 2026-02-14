<?php
session_start();

if (
    !isset($_SESSION['personne']) ||
    !isset($_SESSION['personne']['role']) ||
    $_SESSION['personne']['role'] !== 'responsable'
) {
    header('Location: ../index.php');
    exit;
}
require_once '../../controller/getTechniciensController.php';
require_once __DIR__.'/../../controller/statistiquesController.php';
require_once __DIR__.'/../../controller/calculerDureeController.php';
require_once __DIR__ . '/../../controller/getInterventionsController.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Responsable</title>
  <link rel="icon" type="image/png" href="./../images/logoAUL.jpg">


  <link href="./../styles/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    .page-title{font-weight:700}
    .card-compact .card-header{padding:.55rem .75rem; font-size:.92rem}
    .card-compact .card-body{padding:.75rem}
    .filters-compact .form-label{font-size:.8rem; margin-bottom:.15rem}
    .filters-compact .form-control{padding:.35rem .5rem; font-size:.85rem}
    .table thead th{white-space:nowrap}
    .sidebar a.nav-link{border-radius:.5rem}
    .sidebar a.nav-link:hover{background: rgba(255,255,255,.08)}
    .sidebar .nav-link.active{background:#0d6efd}
    .kpi-card{cursor:pointer}
.kpi-card:hover{transform: translateY(-1px); transition:.15s; box-shadow:0 .5rem 1rem rgba(0,0,0,.08)!important;}

  </style>
</head>

<body class="bg-light">

<div class="container-fluid">
  <div class="row">

    <aside class="col-12 col-md-3 col-lg-2 bg-dark text-white min-vh-100 sidebar">
      <div class="p-3 border-bottom border-secondary">
        <div class="bg-white text-center p-3 border-bottom">
        <a href="./index.php" class="d-inline-block">
          <img src="../images/logo-removebg-preview.png" alt="Logo" class="img-fluid" style="max-height:72px;">
        </a>
      </div>
      </div>

      <nav class="p-2">
        <ul class="nav nav-pills flex-column gap-1">
          <li class="nav-item">
            <a href="./index.php" class="nav-link active">
              Accueil
            </a>
          </li>
        <li class="nav-item">
            <a href="./DemanderIntervention.php" class="nav-link text-white">Mes Demandes</a>
          </li>
          <li class="nav-item">
            <a href="./gererInterventions.php" class="nav-link text-white">Historiques des interventions</a>
          </li>
          <li class="nav-item">
            <a href="./materiels.php" class="nav-link text-white">
              gérer matériels
            </a>
          </li>

          <li class="nav-item">
            <a href="./employe.php" class="nav-link text-white">
              gérer employés
            </a>
          </li>
          <li class="nav-item">
            <a href="./departements.php" class="nav-link text-white">
              gérer departements
            </a>
          </li>
          

        

          <li class="nav-item">
            <a href="../technicien/index.php" class="nav-link text-white">
              🔁 Interface technicien
            </a>
          </li>

          <li class="nav-item mt-2">
            <a href="../../controller/logoutController.php" class="nav-link text-white bg-danger">
              🚪 Déconnexion
            </a>
          </li>
        </ul>

        <div class="small text-white-50 mt-4 px-2">
          © <?= date('Y') ?> — AUL
        </div>
      </nav>
    </aside>


    <main class="col-12 col-md-9 col-lg-10 ">
      <div class="bg-white border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3">
          <div>
            <div class="text-muted small">Dashboard — Interventions</div>
            <h5 class="mb-0">Bonjour <?= htmlspecialchars($_SESSION['personne']['prenom'].' '.$_SESSION['personne']['nom']) ?></h5>
          </div>
          <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
      </div>
           <div class="p-3 p-lg-4">

      <div class="row g-3 mb-3">
        <div class="col-6 col-lg-2">
          <div class="card shadow-sm kpi-card" role="button" data-etat="all">
            <div class="card-body">
              <div class="text-muted small">Total des demandes</div>
              <div class="fs-4 fw-semibold"><?= (int)$kpi['total'] ?></div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <div class="card shadow-sm kpi-card" role="button" data-etat="en attente">
            <div class="card-body">
              <div class="text-muted small">En attente</div>
              <div class="fs-4 fw-semibold"><?= (int)$kpi['attente'] ?></div>
            </div>
          </div>
        </div>


        <div class="col-6 col-lg-2">
          <div class="card shadow-sm kpi-card" role="button" data-etat="affecter">
            <div class="card-body">
              <div class="text-muted small">Affecter a un technicien</div>
              <div class="fs-4 fw-semibold"><?= (int)$kpi['affecter'] ?></div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <div class="card shadow-sm kpi-card" role="button" data-etat="en cours">
            <div class="card-body">
              <div class="text-muted small">En cours</div>
              <div class="fs-4 fw-semibold"><?= (int)$kpi['encours'] ?></div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <div class="card shadow-sm kpi-card" role="button" data-etat="cloturée">
            <div class="card-body">
              <div class="text-muted small">Clôturées</div>
              <div class="fs-4 fw-semibold"><?= (int)$kpi['cloturee'] ?></div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <div class="card shadow-sm border-danger kpi-card" role="button" data-etat="retard">
            <div class="card-body">
              <div class="text-muted small">En retard</div>
              <div class="fs-4 fw-semibold text-danger"><?= (int)$kpi['retard'] ?></div>
            </div>
          </div>
        </div>
</div>
       
  <div class="card shadow-sm mb-4 border-primary">
  <div class="card-body py-2">
    <div class="row g-3 align-items-center">
      <div class="col-12 col-md-auto">
        <span class="fw-bold text-primary"><i class="bi bi-funnel-fill"></i> Filtrage :</span>
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-0"><i class="bi bi-calendar-event me-1"></i>Date Début</label>
        <input type="date" id="global_start" class="form-control form-control-sm">
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-0"><i class="bi bi-calendar-check me-1"></i>Date Fin</label>
        <input type="date" id="global_end" class="form-control form-control-sm">
      </div>
      <div class="col-12 col-md-auto">
        <button class="btn btn-primary btn-sm px-4 mt-md-3" id="btnGlobalFilter">
          <i class="bi bi-arrow-clockwise"></i> Actualiser
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-6 d-flex flex-column gap-3">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-white"><span class="fw-semibold">📊 Par client (période)</span></div>
      <div class="card-body">
        <div class="border rounded-3 p-2 bg-light" style="height: 220px;"><canvas id="chartClients"></canvas></div>
      </div>
    </div>
    <div class="card shadow-sm h-100">
      <div class="card-header bg-white"><span class="fw-semibold">🏢 Par département (période)</span></div>
      <div class="card-body">
        <div class="border rounded-3 p-2 bg-light" style="height: 220px;"><canvas id="chartDeps"></canvas></div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6">
    <div class="card shadow-sm h-100"> <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">🖥 Équipement & Type (période)</span>
        <span class="badge text-bg-secondary">Top 10</span>
      </div>
      <div class="card-body d-flex flex-column gap-4">
        <div>
          <div class="small fw-semibold mb-2">Top équipements</div>
          <div class="border rounded-3 p-2 bg-light" style="height: 200px;"><canvas id="chartEquip"></canvas></div>
        </div>
        <div>
          <div class="small fw-semibold mb-2">Par type</div>
          <div class="border rounded-3 p-2 bg-light" style="height: 200px;"><canvas id="chartTypes"></canvas></div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>


      <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-semibold">Demandes des interventions en attente pour l'affectation a un technicien:</h6>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th class="text-nowrap">ID</th>
                    <th>Description</th>
                    <th class="text-nowrap">Date</th>
                    <th class="text-nowrap">matériel</th>
                    <th class="text-nowrap">État</th>
                    <th class="text-nowrap">Lieu</th>
                    <th class="text-nowrap">Actions</th>
                    <th class="text-nowrap">État</th>
                    <th class="text-nowrap">SLA</th>

                  </tr>
                </thead>

                <tbody>
                <?php
                  $hasRows = false;
                  foreach ($pannes as $r):
                    $etat = htmlspecialchars($r['etatDemande']);
                    if ($etat !== 'annulée' && $etat === 'en attente'):
                      $hasRows = true;
                ?>
                  <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($r['idDemandeIn']) ?></td>
                    <td style="min-width: 240px;"><?= htmlspecialchars($r['description']) ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($r['dateDemande']) ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($r['libelleTypeMateriel']).' '.htmlspecialchars($r['libelleMarque']) ?></td>
                    <td class="text-nowrap">
                      <span class="badge text-bg-warning">en attente</span>
                    </td>
                    <td class="text-nowrap"><?= htmlspecialchars($r['lieuMateriel']) ?></td>
                    <td style="min-width: 280px;">
                      <form method="POST" action="../../controller/affecterDemandeController.php" class="d-flex gap-2">
                        <input type="hidden" name="idDemande" value="<?= htmlspecialchars($r['idDemandeIn']) ?>">

                        <select name="idTechnicien" class="form-select form-select-sm" required>
                          <option value="">-- affecter --</option>
                          <?php foreach ($techniciens as $tech): ?>
                            <option value="<?= htmlspecialchars($tech['id']) ?>">
                              <?= htmlspecialchars($tech['nom'].' '.$tech['prenom']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>

                        <button type="submit" class="btn btn-sm btn-success">
                          Affecter
                        </button>
                      </form>
                    </td>
                    <?php
$etat = $r['etatDemande'] ?? 'en attente';

$etatClass = 'text-bg-secondary';
if ($etat === 'affecter')  $etatClass = 'text-bg-primary';
if ($etat === 'en cours')  $etatClass = 'text-bg-warning';
if ($etat === 'cloturée')  $etatClass = 'text-bg-success';

$h = isset($r['delaiHeures']) ? (int)$r['delaiHeures'] : 0;
$over = isset($r['isOverdue']) ? ((int)$r['isOverdue'] === 1) : false;

$slaClass = 'text-bg-success';
$slaLabel = 'Dans les délais';

if ($over) { $slaClass = 'text-bg-danger'; $slaLabel = 'En retard'; }
elseif ($h >= 96) { $slaClass = 'text-bg-warning'; $slaLabel = 'À risque'; }
?>
<td class="text-nowrap">
  <span class="badge <?= $etatClass ?>"><?= htmlspecialchars($etat) ?></span>
</td>

<td class="text-nowrap">
  <span class="badge <?= $slaClass ?>"><?= $slaLabel ?></span>
  <span class="badge text-bg-secondary ms-1"><?= $h ?>h</span>
</td>

                  </tr>
                <?php
                    endif;
                  endforeach;

                  if (!$hasRows):
                ?>
                  <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                      Aucune demande en attente pour le moment.
                    </td>
                  </tr>
                <?php endif; ?>
                </tbody>
              </table>
            
          </div>
        </div>
      </div>

      <!-- MODAL TERMINER -->
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
                <textarea name="typeIntervention" class="form-control" rows="3" required></textarea>
              </div>

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
<div class="modal fade" id="etatModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="etatModalTitle">Demandes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Date</th>
                <th>Matériel</th>
                <th>Lieu</th>
                <th>État</th>
                <th>SLA</th>
              </tr>
            </thead>
            <tbody id="etatTableBody"></tbody>
          </table>
        </div>

        <div class="text-muted small" id="etatCount"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <a id="btnPrintEtat" class="btn btn-primary" target="_blank">
  Imprimer
</a>


      </div>
    </div>
  </div>
</div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../styles/js/bootstrap.bundle.min.js"></script>

<script>
let chartClients = null;
let chartEquip = null;
let chartTypes = null;
function qs(params){
  const u = new URLSearchParams(params);
  return u.toString();
}
function setDefaultDates(startId, endId){
  const end = new Date();
  const start = new Date();
  start.setDate(end.getDate() - 30);
  document.getElementById(endId).value = end.toISOString().slice(0,10);
  document.getElementById(startId).value = start.toISOString().slice(0,10);
}
setDefaultDates('c_start','c_end');
setDefaultDates('e_start','e_end');
document.getElementById('btnClientStats').addEventListener('click', () => {
  const start = document.getElementById('c_start').value;
  const end   = document.getElementById('c_end').value;
  fetch('../../controller/statsClientPeriodeController.php?' + qs({start, end}))
    .then(r => r.json())
    .then(data => {
      if (chartClients) chartClients.destroy();
      chartClients = new Chart(document.getElementById('chartClients'), {
        type: 'bar',
        data: { labels: data.labels, datasets: [{ data: data.values }] },
        options: {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } }
}
      });
    })
    .catch(console.error);
});
document.getElementById('btnEquipStats').addEventListener('click', () => {
  const start = document.getElementById('e_start').value;
  const end   = document.getElementById('e_end').value;
  fetch('../../controller/statsEquipementTypeController.php?' + qs({start, end}))
    .then(r => r.json())
    .then(data => {
      if (chartEquip) chartEquip.destroy();
      if (chartTypes) chartTypes.destroy();
      chartEquip = new Chart(document.getElementById('chartEquip'), {
        type: 'bar',
        data: { labels: data.equipLabels, datasets: [{ data: data.equipValues }] },
        options: {
  responsive: true,
  maintainAspectRatio: false,
plugins: { legend: { display: false } }
}
      });
      chartTypes = new Chart(document.getElementById('chartTypes'), {
        type: 'doughnut',
        data: { labels: data.typeLabels, datasets: [{ data: data.typeValues }] },
        options: {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { position: 'bottom' } }
}
      });
    })
    .catch(console.error);
});
document.getElementById('btnClientStats').click();
document.getElementById('btnEquipStats').click();
document.querySelectorAll('.terminerBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('idInterventionInput').value = btn.dataset.id;
    document.getElementById('idDemandeIn').value = btn.dataset.iddemande;
  });
});
</script>
<script>
let chartDeps = null;

setDefaultDates('d_start', 'd_end');

document.getElementById('btnDepStats').addEventListener('click', () => {
  const start = document.getElementById('d_start').value;
  const end   = document.getElementById('d_end').value;

  fetch('../../controller/statsDepPeriodeController.php?' + qs({start, end}))
    .then(r => r.json())
    .then(data => {
      if (chartDeps) chartDeps.destroy();
      chartDeps = new Chart(document.getElementById('chartDeps'), {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Interventions',
            data: data.values,
            backgroundColor: '#0d6efd',
            borderRadius: 5
          }]
        },
        options: {
          indexAxis: 'y', 
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 } }
          }
        }
      });
    })
    .catch(console.error);
});
document.getElementById('btnDepStats').click();
                  </script>
<script>

  const demandes = <?= json_encode($pannes, JSON_UNESCAPED_UNICODE) ?>;
  const etatMap = {
    all: null,
    'en attente': "en attente",
    'affecter': "affecter",
    'en cours': "en cours",
    'cloturée': "terminée",
  };
  const etatLabels = {
    all: "Toutes les demandes",
    'en attente': "Demandes — En attente",
    affecter: "Demandes — Affectées",
    'en cours': "Demandes — En cours",
    cloturée: "Demandes — Clôturées",
    'retard': "Demandes — En retard"
  };
  function badgeClassEtat(etat){
    if(etat === 'en attente') return 'text-bg-warning';
    if(etat === 'affecter' || etat === 'affectée') return 'text-bg-primary';
    if(etat === 'en cours') return 'text-bg-info';
    if(etat === 'cloturee' || etat === 'terminée') return 'text-bg-success';
    if(etat === 'annulée') return 'text-bg-danger';
    return 'text-bg-secondary';
  }
  function slaInfo(row){
    const h = row.delaiHeures ? parseInt(row.delaiHeures) : 0;
const over = Number(row.isOverdue) === 1;
    let slaClass = 'text-bg-success', label = 'Dans les délais';
    if (over) { slaClass = 'text-bg-danger'; label = 'En retard'; }
    else if (h >= 96) { slaClass = 'text-bg-warning'; label = 'À risque'; }
    return { h, slaClass, label };
  }
  function renderTable(rows){
    const tbody = document.getElementById('etatTableBody');
    tbody.innerHTML = '';
    rows.forEach(r => {
      const tr = document.createElement('tr');
      const etat = (r.etatDemande || '').trim();
      const materiel = `${r.libelleTypeMateriel || ''} ${r.libelleMarque || ''}`.trim();
      let slaHtml = `<span class="text-muted">—</span>`;
      if (etat !== 'terminée' && etat !== 'cloturee' && etat !== 'annulée') {
        const sla = slaInfo(r);
        slaHtml = `
          <span class="badge ${sla.slaClass}">${sla.label}</span>
          <span class="badge text-bg-secondary ms-1">${sla.h}h</span>
        `;
      }
      tr.innerHTML = `
        <td class="fw-semibold">${r.idDemandeIn ?? ''}</td>
        <td style="min-width:240px">${r.description ?? ''}</td>
        <td class="text-nowrap">${r.dateDemande ?? ''}</td>
        <td class="text-nowrap">${materiel}</td>
        <td class="text-nowrap">${r.lieuMateriel ?? ''}</td>
        <td class="text-nowrap"><span class="badge ${badgeClassEtat(etat)}">${etat}</span></td>
        <td class="text-nowrap">${slaHtml}</td>
      `;
      tbody.appendChild(tr);
    });
    document.getElementById('etatCount').textContent = `Résultats: ${rows.length}`;
  }
  function filterByEtat(etatKey){
    let rows = [];   
    if(etatKey === 'retard'){
        rows = demandes.filter((d)=>d.isOverdue);
        document.getElementById('etatModalTitle').textContent = "Demandes en Retard (> 120h)";
    } 
    else if(etatKey === 'all'){
        rows = [...demandes];
        document.getElementById('etatModalTitle').textContent = "Toutes les demandes";
    } 
    else {
        const wanted = etatMap[etatKey];
        rows = demandes.filter(r => (r.etatDemande || '').trim() === wanted);
        document.getElementById('etatModalTitle').textContent = etatLabels[etatKey] || 'Demandes';
    }

    rows.sort((a,b) => (b.dateDemande || '').localeCompare(a.dateDemande || ''));
    renderTable(rows);
    new bootstrap.Modal(document.getElementById('etatModal')).show();

    const printBtn = document.getElementById('btnPrintEtat');
    printBtn.href = `../../controller/imprimerEtatGlobalController.php?etat=${etatKey}`;
        new bootstrap.Modal(document.getElementById('etatModal')).show();
}
  document.querySelectorAll('.kpi-card').forEach(card => {
    card.addEventListener('click', () => {
      filterByEtat(card.dataset.etat);
    });
  });
setDefaultDates('global_start', 'global_end');
function refreshAllStats() {
    const start = document.getElementById('global_start').value;
    const end = document.getElementById('global_end').value;
    const params = qs({ start, end });

    const colorPrimary = '#198754'; 
    const colorSecondary = '#dc3545'; 
    const colorAccent = '#0d6efd'; 
    fetch('../../controller/statsClientPeriodeController.php?' + params)
        .then(r => r.json())
        .then(data => {
            if (chartClients) chartClients.destroy();
            chartClients = new Chart(document.getElementById('chartClients'), {
                type: 'bar',
                data: { 
                    labels: data.labels, 
                    datasets: [{ 
                        label: 'Demandes', 
                        data: data.values, 
                        backgroundColor: colorPrimary 
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }}
            });
        });

    fetch('../../controller/statsEquipementTypeController.php?' + params)
        .then(r => r.json())
        .then(data => {
            if (chartEquip) chartEquip.destroy();
            if (chartTypes) chartTypes.destroy();


            chartEquip = new Chart(document.getElementById('chartEquip'), {
                type: 'bar',
                data: { labels: data.equipLabels, datasets: [{ data: data.equipValues, backgroundColor: '#495057' }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }}
            });

            chartTypes = new Chart(document.getElementById('chartTypes'), {
                type: 'doughnut',
                data: { 
                    labels: data.typeLabels, 
                    datasets: [{ 
                        data: data.typeValues, 
                        backgroundColor: [colorPrimary, colorSecondary, colorAccent] 
                    }] 
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });

    fetch('../../controller/statsDepPeriodeController.php?' + params)
        .then(r => r.json())
        .then(data => {
            if (chartDeps) chartDeps.destroy();
            chartDeps = new Chart(document.getElementById('chartDeps'), {
                type: 'bar',
                data: { 
                    labels: data.labels, 
                    datasets: [{ data: data.values, backgroundColor: '#20c997' }] 
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        });
}
document.getElementById('btnGlobalFilter').addEventListener('click', refreshAllStats);
refreshAllStats();
</script>

</body>
</html>
