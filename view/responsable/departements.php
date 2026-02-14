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

require_once __DIR__ . '/../../controller/getDepartementController.php'; // خاص يجيب $departements
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Départements</title>
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
            <a href="./departements.php" class="nav-link active">
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
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepModal">
              + Ajouter département
            </button>
            <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
          </div>
        </div>
      </div>

      <div class="p-3 p-lg-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div>
            <h6 class="mb-0">Les départements enregistrés</h6>
            <div class="text-muted small">Liste complète des départements et étages</div>
          </div>
        </div>


        <div class="card shadow-sm">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th>Département</th>
                    <th class="text-nowrap">N° étage</th>
                    <th class="text-nowrap">Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach($departements as $d): ?>
                    <tr>
                      <td class="fw-semibold">
                        <?= htmlspecialchars($d['nom']) ?>
                      </td>
                      <td class="text-nowrap">
                        <span class="badge text-bg-secondary">
                          <?= htmlspecialchars($d['numEtage']) ?>
                        </span>
                      </td>

                      <td class="text-nowrap">
                        <button type="button"
        class="btn btn-sm btn-outline-secondary"
        data-bs-toggle="modal"
        data-bs-target="#editDepModal<?= (int)$d['idDep'] ?>">
  Modifier
</button>


                        <a class="btn btn-sm btn-outline-danger"
                           href="../../controller/deleteDepartementController.php?id=<?= urlencode($d['idDep']) ?>"
                           onclick="return confirm('Supprimer ce département ?')">
                          Supprimer
                        </a>
                      </td>
                    </tr>

                    

                  <?php endforeach; ?>
                  
                </tbody>

              </table>
              
            </div>
          </div>
        </div>

      </div>
    </main>

  </div>
</div>
<?php foreach($departements as $d): ?>

<div class="modal fade" id="editDepModal<?= (int)$d['idDep'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="POST" action="../../controller/updateDepartementController.php">
                            <div class="modal-header">
                              <h5 class="modal-title">Modifier département</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                              <input type="hidden" name="idDep" value="<?= (int)$d['idDep'] ?>">

                              <div class="mb-3">
                                <label class="form-label">Nom département</label>
                                <input type="text" name="nom" class="form-control"
                                       value="<?= htmlspecialchars($d['nom']) ?>" required>
                              </div>

                              <div class="mb-0">
                                <label class="form-label">N° étage</label>
                                <input type="number" name="numEtage" class="form-control"
                                       value="<?= (int)$d['numEtage'] ?>" required>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                              <button type="submit" name="updateDep" class="btn btn-success">Enregistrer</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php endforeach; ?>
<div class="modal fade" id="addDepModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="../../controller/addDepartementController.php">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter département</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nom département</label>
            <input type="text" name="nom" class="form-control" required>
          </div>

          <div class="mb-0">
            <label class="form-label">N° étage</label>
            <input type="number" name="numEtage" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="addDep" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../styles/js/bootstrap.bundle.min.js"></script>
</body>
</html>
