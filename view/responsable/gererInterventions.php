
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
require_once __DIR__.'/../../controller/historiquesController.php';
require_once __DIR__.'/../../controller/typesInterventionsController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Diagnostics</title>

  <link rel="stylesheet" href="../styles/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container-fluid">
  <div class="row">

    <aside class="col-12 col-md-3 col-lg-2 bg-dark text-white min-vh-100">
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
            <a href="./index.php" class="nav-link text-white">
              Accueil
            </a>
          </li>
        <li class="nav-item">
            <a href="./DemanderIntervention.php" class="nav-link text-white">Mes Demandes</a>
          </li>
          <li class="nav-item">
            <a href="./gererInterventions.php" class="nav-link active">Historiques des interventions</a>
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

    <main class="col-12 col-md-9 col-lg-10">

      <div class="bg-white border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3">
          <div>
            <div class="text-muted small">Responsable</div>
            <h5 class="mb-0">
              Bonjour
              <span class="fw-semibold">
                <?= htmlspecialchars($_SESSION['personne']['prenom'].' '.$_SESSION['personne']['nom']) ?>
              </span>
            </h5>
          </div>

          <div class="d-flex gap-2">

            <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
          </div>
        </div>
      </div>

      <div class="p-3 p-lg-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title text-dark"><i class="bi bi-archive-fill text-secondary me-2"></i>Historique des Interventions</h2>
        <div class="d-flex gap-2">
            <a href="../../controller/imprimerDiagnosticsController.php" class="btn btn-danger btn-sm" id="exportPDF"><i class="bi bi-file-earmark-pdf"></i> Imprimer Rapport</a href="../../controller/imp">
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchHistory" class="form-control" placeholder="Rechercher par demandeur, S/N ou matériel...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterType" class="form-select">
  <option value="">Tous les types d'intervention</option>

  <?php if (!empty($typesIntervention) && is_array($typesIntervention)): ?>
    <?php foreach ($typesIntervention as $ti): ?>
      <option value="<?= htmlspecialchars($ti['typeIntervention'] ?? '') ?>">
        <?= htmlspecialchars($ti['typeIntervention'] ?? '') ?>
      </option>
    <?php endforeach; ?>
  <?php endif; ?>
</select>
                </div>
                <div class="col-md-3">
                    <input type="month" id="filterMonth" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-3">Demandeur / Lieu</th>
                            <th>Matériel & S/N</th>
                            <th>Description Problème</th>
                            <th>Cycle d'Intervention</th>
                            <th>Diagnostic & Action</th>
                            <th>Technicien</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                        <?php foreach ($historique as $row): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['demandeur']) ?></div>
                                <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['lieuMateriel']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border mb-1"><?= htmlspecialchars($row['materiel']) ?></span>
                                <div class="small fw-semibold">S/N: <?= htmlspecialchars($row['numSerie']) ?></div>
                            </td>
                            <td>
                                <div class="text-truncate" style="" title="<?= htmlspecialchars($row['description']) ?>">
                                    <?= htmlspecialchars($row['description']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="small"><span class="text-primary">Début:</span> <?= $row['dateDebut'] ?></div>
                                <div class="small"><span class="text-danger">Fin:</span> <?= $row['dateFin'] ?></div>
                            </td>
                            <td>
                                <div class="small fw-bold text-info"><?= htmlspecialchars($row['typeIntervention']) ?></div>
                                <div class="small fst-italic text-muted text-truncate" style="max-width: 200px;"><?= htmlspecialchars($row['action']) ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:24px; height:24px; font-size:10px;">
                                        <?= strtoupper(substr($row['technicien'], 0, 1)) ?>
                                    </div>
                                    <span class="small"><?= htmlspecialchars($row['technicien']) ?></span>
                                </div>
                            </td>
                            
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <nav class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">Affichage de <?= count($historique) ?> interventions clôturées</div>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Précédent</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">Suivant</a></li>
                </ul>
            </nav>
        </div>
    </div>
      </div>
    </main>

  </div>
</div>

<script src="../styles/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const globalSearch = document.getElementById('searchHistory');   
  const filterType   = document.getElementById('filterType');
  const filterMonth  = document.getElementById('filterMonth');
  const tbody        = document.getElementById('historyTableBody'); 

  function filterTable() {
    const searchText = (globalSearch.value || '').toLowerCase().trim();
    const typeValue  = (filterType.value || '').toLowerCase().trim();
    const monthValue = (filterMonth.value || '').trim(); 

    const rows = tbody.querySelectorAll('tr');

    rows.forEach(row => {
      const rowFullText = row.textContent.toLowerCase();

      const rowType = (row.querySelector('td:nth-child(5)')?.textContent || '').toLowerCase();

      const rowDates = row.querySelector('td:nth-child(4)')?.textContent || '';

      const matchesSearch = rowFullText.includes(searchText);
      const matchesType   = typeValue === '' || rowType.includes(typeValue);
      const matchesMonth  = monthValue === '' || rowDates.includes(monthValue);

      row.style.display = (matchesSearch && matchesType && matchesMonth) ? '' : 'none';
    });
  }

  globalSearch.addEventListener('input', filterTable);
  filterType.addEventListener('change', filterTable);
  filterMonth.addEventListener('change', filterTable);
});
function showDetails(id) {
    fetch(`../../controller/getInterventionDetails.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            $('#detailsModal').modal('show');
        });
}
</script>
</body>
</html>
