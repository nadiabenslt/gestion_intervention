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

require_once __DIR__.'/../../controller/getPanneController.php'; // كيجبد $pannes
require_once __DIR__.'/../../controller/getMaterielByPersonneController.php';
require_once __DIR__.'/../../controller/getPrioriteController.php';
require_once __DIR__.'/../../controller/getPersonneInfosController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Demandes d'intervention</title>

  <link rel="stylesheet" href="../styles/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            <a href="./DemanderIntervention.php" class="nav-link active">Mes Demandes</a>
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

    <main class="col-12 col-md-9 col-lg-10">

      <div class="bg-white border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3">
          <div>
            <div class="text-muted small">Responsable</div>
            <h5 class="mb-0">
              Bonjour
              <span class="fw-semibold">
                <?= htmlspecialchars($_SESSION['personne']['prenom'].' '.$_SESSION['personne']['nom']); ?>
              </span>
            </h5>
            <div class="text-muted small">Gestion des demandes d’intervention</div>
          </div>

          <div class="d-flex gap-2">
           <button type="button" class="btn btn-primary btn-sm"
        data-bs-toggle="modal" data-bs-target="#declarerPanneModal">
  <i class="bi bi-exclamation-triangle me-1"></i> Déclarer une panne
</button>

            <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">
              <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
            </a>
          </div>
        </div>
      </div>

      <div class="p-3 p-lg-4">

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
          <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <div class="fw-semibold">
              <i class="bi bi-list-check me-2"></i> Liste des demandes
            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th class="text-nowrap">Matériel</th>
                    <th class="text-nowrap">Date</th>
                    <th>Description</th>
                    <th class="text-nowrap">État</th>
                    <th class="text-nowrap">Priorité</th>
                    <th class="text-nowrap text-end">Actions</th>
                  </tr>
                </thead>

                <tbody>
                <?php if (!empty($pannes)): ?>
                  <?php foreach ($pannes as $p): ?>
                    <?php
                      $etat = $p['etatDemande'] ?? '';
                      $badgeEtat = match($etat) {
                        'en attente' => 'warning',
                        'affecter'   => 'primary',
                        'en cours'   => 'info',
                        'cloturée'   => 'success',
                        'annulée'    => 'secondary',
                        default      => 'dark'
                      };

                      $prio = $p['priorite'] ?? '';
                      $badgePrio = match(strtolower($prio)) {
                        'haute'   => 'danger',
                        'moyenne' => 'warning',
                        'basse'   => 'success',
                        default   => 'secondary'
                      };
                    ?>
                    <tr>
                      <td class="fw-semibold">
                        <?= htmlspecialchars(($p['libelleTypeMateriel'] ?? '').' '.($p['libellemarque'] ?? '')); ?>
                      </td>
                      <td class="text-nowrap"><?= htmlspecialchars($p['dateDemande'] ?? '') ?></td>
                      <td style="min-width:260px; max-width:520px;">
                        <div class="text-truncate">
                          <?= htmlspecialchars($p['description'] ?? '') ?>
                        </div>
                      </td>
                      <td class="text-nowrap">
                        <span class="badge text-bg-<?= $badgeEtat ?>"><?= htmlspecialchars($etat) ?></span>
                      </td>
                      <td class="text-nowrap">
                        <span class="badge text-bg-<?= $badgePrio ?>"><?= htmlspecialchars($prio) ?></span>
                      </td>

                      <td class="text-end text-nowrap">
                        <?php if ($etat === 'en attente'): ?>
                          <a href="../../controller/annulerDemandeController.php?id=<?= (int)$p['idDemandeIn']; ?>"
                             class="btn btn-outline-danger btn-sm" title="Annuler">
                            <i class="bi bi-x-circle"></i>
                          </a>
                        <?php endif; ?>

                        <?php if ($etat === 'annulée'): ?>
                          <a href="../../controller/declarerPanneController.php?id=<?= (int)$p['idDemandeIn'];?>"
                             class="btn btn-outline-primary btn-sm" title="Redéclarer">
                            <i class="bi bi-arrow-repeat"></i>
                          </a>
                        <?php endif; ?>

                        <?php if($etat == 'en attente' || $etat == 'annulée'): ?>
                          <a href="../../controller/deleteDemandeController.php?id=<?= (int)$p['idDemandeIn']; ?>"
                             class="btn btn-outline-danger btn-sm"
                             onclick="return confirm('Voulez-vous vraiment supprimer cette demande ?');"
                             title="Supprimer">
                            <i class="bi bi-trash"></i>
                          </a>
                        <?php endif; ?>

                        <a href="../../controller/imprimerDemandeController.php?id=<?= $p['idDemandeIn']; ?>"
                           class="btn btn-outline-dark btn-sm" title="Imprimer">
                          <i class="bi bi-printer"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Aucune demande trouvée.</td>
                  </tr>
                <?php endif; ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>

      </div>
    </main>

  </div>
</div>
<div class="modal fade" id="declarerPanneModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="../../controller/declarerPanneController.php">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-exclamation-triangle me-2"></i> Déclarer une panne
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-12">
              <label class="form-label">Matériel</label>
              <select name="idMateriel" class="form-select" required>
                <option value="" disabled selected>-- Choisir un matériel --</option>
                <?php foreach ($materiels as $m) : ?>
                  <option value="<?= (int)$m['idMateriel']; ?>">
                    <?= htmlspecialchars($m['typeM']." ".$m['marqueM']); ?>
                  </option>
                <?php endforeach ;?>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" rows="4" class="form-control"
                        placeholder="Décrivez la panne (symptômes, messages d'erreur, etc.)" required></textarea>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Priorité</label>
              <select name="priorite" class="form-select" required>
                <option value="" disabled selected>-- Choisir une priorité --</option>
                <?php foreach ($priorites as $p) : ?>
                  <option value="<?= htmlspecialchars($p['libellePriorite']); ?>">
                    <?= htmlspecialchars($p['libellePriorite']); ?>
                  </option>
                <?php endforeach ;?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Lieu du matériel</label>
              <input type="text" class="form-control" name="lieuMateriel"
                     value="<?= htmlspecialchars($personneInfos['dep'].' - Salle: '.$personneInfos['numSalle']) ?>"
                     readonly>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Annuler
          </button>
          <button type="reset" class="btn btn-outline-secondary">
            <i class="bi bi-eraser me-1"></i> Réinitialiser
          </button>
          <button type="submit" name="declarer" class="btn btn-primary">
            <i class="bi bi-send me-1"></i> Déclarer
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="../styles/js/bootstrap.bundle.min.js"></script>
</body>
</html>
