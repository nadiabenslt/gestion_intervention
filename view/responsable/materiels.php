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

require_once __DIR__.'/../../controller/modifierMaterielController.php';
require_once __DIR__.'/../../controller/getTypeMaterielController.php';
require_once __DIR__.'/../../controller/getMarquesController.php';
require_once __DIR__.'/../../controller/getDepartementController.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Matériels</title>

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
            <a href="./materiels.php" class="nav-link active">
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
                <?php echo htmlspecialchars($_SESSION['personne']['prenom'].' '.$_SESSION['personne']['nom']); ?>
              </span>
            </h5>
          </div>

          <div class="d-flex gap-2">
             <button type="button"
        class="btn btn-primary btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#newMateriel">
  + Ajouter matériel
</button>
            <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
          </div>
        </div>
      </div>

      <div class="p-3 p-lg-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div>
            <h6 class="mb-0">Les matériels existants dans l’agence</h6>
            <div class="text-muted small">Liste complète des équipements</div>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-body p-0">

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th class="text-nowrap">Numéro Série</th>
                    <th class="text-nowrap">Type</th>
                    <th class="text-nowrap">Marque</th>
                    <th class="text-nowrap">Date Achat</th>
                    <th class="text-nowrap">Prix</th>
                    <th>Caractéristique</th>
                    <th class="text-nowrap">Location</th>
                    <th class="text-nowrap">Actions</th>
                  </tr>
                </thead>

                <tbody>
                <?php if (!empty($materiels)): ?>
                  <?php foreach ($materiels as $m): ?>
                    <tr>
                      <td class="fw-semibold text-nowrap"><?php echo htmlspecialchars($m['numSerie']); ?></td>
                      <td class="text-nowrap"><?php echo htmlspecialchars($m['libelleTypeMateriel']); ?></td>
                      <td class="text-nowrap"><?php echo htmlspecialchars($m['libelleMarque']); ?></td>
                      <td class="text-nowrap"><?php echo htmlspecialchars($m['dateAchat']); ?></td>
                      <td class="text-nowrap"><?php echo htmlspecialchars($m['prix']); ?></td>
                      <td style="min-width:220px;"><?php echo htmlspecialchars($m['caracteristiques']); ?></td>

                      <td class="text-nowrap">
                        <?php if ($m['salle'] === 'affecter'): ?>
                          <button type="button"
        class="btn btn-sm btn-outline-primary"
        data-bs-toggle="modal"
        data-bs-target="#affectModal<?= (int)$m['idMateriel'] ?>">
  Affecter
</button>

                        <?php else: ?>
                          <span class="badge text-bg-success">
                            <?php echo htmlspecialchars($m['salle']); ?>
                          </span>
                        <?php endif; ?>
                      </td>

                      <td class="text-nowrap">
                        <button type="button"
        class="btn btn-sm btn-outline-secondary"
        data-bs-toggle="modal"
        data-bs-target="#editMaterielModal<?= (int)$m['idMateriel'] ?>">
  Modifier
</button>


                        <a class="btn btn-sm btn-outline-danger"
                           href="../../controller/supprimerMaterielController.php?id=<?= urlencode($m['idMateriel']) ?>"
                           onclick="return confirm('Supprimer ce matériel ?')">
                          Supprimer
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                      Aucun matériel trouvé.
                    </td>
                  </tr>
                <?php endif; ?>
                </tbody>

              </table>
              <?php foreach ($materiels as $m): ?>
  <?php if (($m['salle'] ?? '') === 'affecter'): ?>

  <div class="modal fade" id="affectModal<?= (int)$m['idMateriel'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">

        <form method="POST" action="../../controller/affectationController.php">
          <div class="modal-header">
            <h5 class="modal-title">Affecter matériel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="idMateriel" value="<?= (int)$m['idMateriel'] ?>">

            <div class="alert alert-light border mb-3">
              <div class="fw-semibold">Matériel</div>
              <div class="text-muted small">
                N° Série: <?= htmlspecialchars($m['numSerie']) ?> —
                <?= htmlspecialchars($m['libelleTypeMateriel'] ?? '') ?> /
                <?= htmlspecialchars($m['libelleMarque'] ?? '') ?>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Numéro Série</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($m['numSerie']) ?>" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Date Affectation</label>
                <input type="date" name="dateAffectation" class="form-control" required>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Département</label>
                <select class="form-select dep-select" name="departement" data-materiel="<?= (int)$m['idMateriel'] ?>" required>
                  <option value="">-- choisir un département --</option>
                  <?php foreach ($departements as $d): ?>
                    <option value="<?= (int)$d['idDep'] ?>"><?= htmlspecialchars($d['nom']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Salle</label>
                <select class="form-select salle-select" name="salle" id="salle<?= (int)$m['idMateriel'] ?>" required>
                  <option value="">-- choisir une salle --</option>
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" name="affecterMateriel" class="btn btn-success">Affecter</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <?php endif; ?>
<?php endforeach; ?>
              <?php foreach ($materiels as $m): ?>
<div class="modal fade" id="editMaterielModal<?= (int)$m['idMateriel'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="POST" action="../../controller/modifierMaterielController.php">
        <div class="modal-header">
          <h5 class="modal-title">Modifier matériel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idMateriel" value="<?= (int)$m['idMateriel'] ?>">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Numéro Série</label>
              <input type="text" name="numSerie" class="form-control"
                     value="<?= htmlspecialchars($m['numSerie'] ?? '') ?>" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Type matériel</label>
              <select name="typeM" class="form-select" required>
                <?php foreach ($types as $t): ?>
                  <option value="<?= (int)$t['idTypeMateriel'] ?>"
                    <?= ((int)$t['idTypeMateriel'] === (int)($m['idTypeMateriel'] ?? 0)) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['libelleTypeMateriel']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Marque</label>
              <select name="marque" class="form-select" required>
                <?php foreach ($marques as $mk): ?>
                  <option value="<?= (int)$mk['idMarque'] ?>"
                    <?= ((int)$mk['idMarque'] === (int)($m['idMarque'] ?? 0)) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mk['libelleMarque']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Date Achat</label>
              <input type="date" name="dateAchat" class="form-control"
                     value="<?= htmlspecialchars($m['dateAchat'] ?? '') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Prix</label>
              <input type="text" name="prixAchat" class="form-control"
                     value="<?= htmlspecialchars($m['prix'] ?? '') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Caractéristique</label>
              <input type="text" name="caracteristique" class="form-control"
                     value="<?= htmlspecialchars($m['caracteristiques'] ?? '') ?>">
            </div>

            <!-- إذا زدتي status -->
            <?php if (isset($m['status'])): ?>
            <div class="col-12 col-md-6">
              <label class="form-label">Statut</label>
              <select name="status" class="form-select">
                <?php
                  $st = $m['status'] ?? 'fonctionnel';
                  $opts = ['fonctionnel','en panne','hors service'];
                  foreach($opts as $o):
                ?>
                  <option value="<?= $o ?>" <?= ($st === $o) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($o) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="updateMateriel" class="btn btn-success">Enregistrer</button>
        </div>
      </form>

    </div>
  </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="newMateriel" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="POST" action="../../controller/NewMaterielController.php">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter matériel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-12 col-md-6">
              <label class="form-label">Numéro Série</label>
              <input type="text" name="numSerie" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Type matériel</label>
              <select name="typeM" class="form-select" required>
                <option value="">-- choisir --</option>
                <?php foreach ($types as $type): ?>
                  <option value="<?= (int)$type['idTypeMateriel'] ?>">
                    <?= htmlspecialchars($type['libelleTypeMateriel']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Marque</label>
              <select name="marque" class="form-select" required>
                <option value="">-- choisir --</option>
                <?php foreach ($marques as $marque): ?>
                  <option value="<?= (int)$marque['idMarque'] ?>">
                    <?= htmlspecialchars($marque['libelleMarque']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Date Achat</label>
              <input type="date" name="dateAchat" class="form-control">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Prix</label>
              <input type="text" name="prixAchat" class="form-control" placeholder="Ex: 2500">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Caractéristique</label>
              <input type="text" name="caracteristique" class="form-control" placeholder="Ex: i5, 8GB RAM...">
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="ajouterMateriel" class="btn btn-success">Ajouter</button>
        </div>
      </form>

    </div>


  </div>
</div>

            </div>

          </div>
        </div>

      </div>
      
    </main>

  </div>
</div>

<script src="../styles/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.dep-select').forEach(select => {
  select.addEventListener('change', function() {
    const depId = this.value;
    const materielId = this.getAttribute('data-materiel');
    const salleSelect = document.getElementById('salle' + materielId);

    salleSelect.innerHTML = `<option value="">-- choisir une salle --</option>`;

    if (!depId) return;

    fetch(`../../controller/getSallesController.php?id=${depId}`)
      .then(res => res.json())
      .then(data => {
        data.forEach(salle => {
          salleSelect.innerHTML += `<option value="${salle.idSalle}">${salle.numSalle}</option>`;
        });
      })
      .catch(err => console.error(err));
  });
});
</script>

</body>
</html>
