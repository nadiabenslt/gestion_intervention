<?php

session_start();

if (
    !isset($_SESSION['personne']) ||
    !isset($_SESSION['personne']['role']) ||
    $_SESSION['personne']['role'] !== 'employe'
) {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__.'/../../controller/getPanneController.php';
require_once __DIR__.'/../../controller/getMaterielByPersonneController.php';
require_once __DIR__.'/../../controller/getPrioriteController.php';
require_once __DIR__.'/../../controller/getPersonneInfosController.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard employe</title>
<link rel="icon" type="image/jpg" href="../images/logoAUL.jpg">

<link href="./../styles/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2" href="./index.php">
      <img src="../images/logo-removebg-preview.png" alt="logo" style="height:46px;">
    </a>

    <div class="ms-auto d-flex align-items-center gap-3">
      <div class="text-end">
        <div class="fw-semibold">
          <?= htmlspecialchars($_SESSION['personne']['prenom'].' '.$_SESSION['personne']['nom']) ?>
        </div>
        <small class="text-muted">Employé</small>
      </div>

      <a href="../../controller/logoutController.php" class="btn btn-danger btn-sm">
        <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
      </a>
    </div>
  </div>
</nav>

<div class="container-fluid py-4">

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
        <i class="bi bi-list-check me-2"></i> Mes demandes
      </div>

       <button type="button"
        class="btn btn-primary btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#newDemande">
 <i class="bi bi-plus-circle me-1"></i> Déclarer une panne
</button>

    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Matériel</th>
              <th>Date</th>
              <th>Description</th>
              <th>État</th>
              <th>Priorité</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($pannes as $p): ?>
            <?php
              $etat = $p['etatDemande'];
              $badgeEtat = match($etat) {
                'en attente' => 'warning',
                'annulée'    => 'secondary',
                'traitée'    => 'success',
                default      => 'info'
              };

              $prio = $p['priorite'];
              $badgePrio = match(strtolower($prio)) {
                'haute'  => 'danger',
                'moyenne'=> 'warning',
                'basse'  => 'success',
                default  => 'primary'
              };
            ?>
            <tr>
              <td class="fw-semibold">
                <?= htmlspecialchars($p['libelleTypeMateriel']." ".$p['libellemarque']); ?>
              </td>
              <td><?= htmlspecialchars($p['dateDemande']); ?></td>
              <td style="max-width: 420px;" class="text-truncate">
                <?= htmlspecialchars($p['description']); ?>
              </td>
              <td><span class="badge text-bg-<?= $badgeEtat ?>"><?= htmlspecialchars($etat) ?></span></td>
              <td><span class="badge text-bg-<?= $badgePrio ?>"><?= htmlspecialchars($prio) ?></span></td>

              <td class="text-end">

                <?php if($etat === 'en attente'): ?>
                  <a href="../../controller/annulerDemandeController.php?id=<?= $p['idDemandeIn']; ?>"
                     class="btn btn-outline-danger btn-sm" title="Annuler">
                    <i class="bi bi-x-circle"></i>
                  </a>
                <?php endif; ?>

                <?php if($etat === 'annulée'): ?> 
                  <a href="../../controller/declarerPanneController.php?id=<?= $p['idDemandeIn'];?>"
                     class="btn btn-outline-primary btn-sm" title="Redéclarer">
                    <i class="bi bi-arrow-repeat"></i>
                  </a>
                <?php endif; ?>

                <?php if($etat == 'en attente' || $etat == 'annulée' || $etat=='terminée'): ?>
                  <a href="../../controller/deleteDemandeController.php?id=<?= $p['idDemandeIn']; ?>"
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
          </tbody>
        </table>
<div class="modal fade" id="newDemande" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

      <div class="card shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
          <div class="fw-semibold">
            <i class="bi bi-exclamation-triangle me-2"></i> Déclarer une panne
          </div>

          <a href="./index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Retour
          </a>
        </div>

        <div class="card-body">
          <form method="post" action="../../controller/declarerPanneController.php" class="row g-3">

            <div class="col-12">
              <label class="form-label">Matériel</label>
              <select name="idMateriel" class="form-select" required>
                <option value="" disabled selected>-- Choisir un matériel --</option>
                <?php foreach ($materiels as $m) : ?>
                  <option value="<?= $m['idMateriel']; ?>">
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

            <div class="col-12 d-flex justify-content-end gap-2 pt-2">
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
  </div>
</div>
</div>
</div>
</div>
      </div>
    </div>
  </div>
  


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
  new bootstrap.Popover(el);
});
</script>

</body>
</html>
