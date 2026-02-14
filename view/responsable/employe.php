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

require_once __DIR__.'/../../controller/getEmployesController.php'; 
require_once __DIR__.'/../../controller/getDepartementController.php';


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employés</title>
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
            <a href="./employe.php" class="nav-link active">
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
            <h5 class="mb-0">Gestion des employés</h5>
          </div>
          <a href="../../controller/logoutController.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
      </div>

      <div class="p-3 p-lg-4">
      
        <div class="card shadow-sm mb-3">

          <div class="card-body">
            <div class="row g-2 align-items-center">
              <div class="col-12 col-md-auto">
                <button type="button" class="btn btn-primary w-100"
        data-bs-toggle="modal" data-bs-target="#addEmployeModal">
  + Ajouter employé
</button>

              </div>

              <div class="col-12 col-md">
                <form class="row g-2 align-items-center" method="GET">
  <div class="col-12 col-md">
    <input name="q" class="form-control" value="<?= htmlspecialchars($pagination['q']) ?>"
           placeholder="Rechercher...">
  </div>

  <div class="col-12 col-md-auto">
    <select name="active" class="form-select">
      <option value="all" <?= $pagination['active']==='all'?'selected':'' ?>>Tous</option>
      <option value="active" <?= $pagination['active']==='active'?'selected':'' ?>>Actifs</option>
      <option value="inactive" <?= $pagination['active']==='inactive'?'selected':'' ?>>Désactivés</option>
    </select>
  </div>

  <input type="hidden" name="per_page" value="<?= (int)$pagination['perPage'] ?>">
  <div class="col-12 col-md-auto">
    <button class="btn btn-outline-primary w-100">Rechercher</button>
  </div>
</form>

              </div>
            </div>
          </div>
        </div>
        <div class="row g-3" id="cardsContainer">

          <?php if (!empty($employes)): ?>
            <?php foreach ($employes as $e): 
              $id = $e['id'];
              $nom = htmlspecialchars($e['nomEmploye'] ?? '');
              $prenom = htmlspecialchars($e['prenom'] ?? '');
              $email = htmlspecialchars($e['email'] ?? '');
              $departement = htmlspecialchars($e['nomDep']);
              $role = htmlspecialchars($e['role'] ?? '');
              $matricule = htmlspecialchars($e['matricule'] ?? '');
              $isActive=$e['isActive'];
            ?>
              <div class="col-12 col-md-6 col-lg-4 employe-card"
                   data-search="<?= strtolower($nom.' '.$prenom.' '.$email.' '.$departement.' '.$role.' '.$matricule) ?>">
                <div class="card shadow-sm h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                      <div>
                        <h6 class="mb-1"><?php echo $nom.' '.$prenom ; ?></h6>
                        <div class="text-muted small"><?= $email ?></div>
                      </div>
                      <span class="badge text-bg-dark"><?= $role ?: '—' ?></span>
                    </div>

                    <hr>

                    <div class="small">
                      <div class="mb-1">
                        <span class="text-muted">Département:</span>
                        <span class="fw-semibold"><?= $departement ?: '—' ?></span>
                      </div>

                      <div class="mb-1">
                        <span class="text-muted">Matricule:</span>
                        <span class="fw-semibold"><?= $matricule ?: '—' ?></span>
                      </div>
                    </div>
                  </div>

                  <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <?php if ($id !== null): ?>
                      
                      <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal" data-bs-target="#editEmployeModal<?= (int)$id ?>">
                      Modifier
                      </button>
                      <?php if($isActive==1): ?>
                      <a href="../../controller/desactiverEmployeController.php?id=<?= urlencode($id) ?>"
                         class="btn btn-sm btn-outline-danger"
                         onclick="return confirm('Desactiver le compte de cet employé ?')">
                        Désactiver
                      </a>
                      <?php else: ?>
                        <a href="../../controller/desactiverEmployeController.php?idD=<?= urlencode($id) ?>"
                         class="btn btn-sm btn-outline-success"
                         onclick="return confirm('Activer le compte de cet employé ?')">
                        Activer
                      </a>
                      <?php endif; ?>
                    <?php else: ?>
                      <button class="btn btn-sm btn-outline-secondary" disabled>Modifier</button>
                      <button class="btn btn-sm btn-outline-danger" disabled>désactiver</button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-secondary mb-0">
                Aucun employé trouvé.
              </div>
            </div>
          <?php endif; ?>
          <?php
function pageUrl($p, $q, $active, $perPage) {
  $qs = http_build_query([
    'page' => $p,
    'q' => $q,
    'active' => $active,
    'per_page' => $perPage,
  ]);
  return "?$qs";
}

$page = $pagination['page'];
$totalPages = $pagination['totalPages'];
$q = $pagination['q'];
$active = $pagination['active'];
$perPage = $pagination['perPage'];
?>

<nav class="mt-4 d-flex justify-content-center">
  <ul class="pagination">

    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= pageUrl($page-1, $q, $active, $perPage) ?>">Précédent</a>
    </li>

    <?php
      $start = max(1, $page - 2);
      $end = min($totalPages, $page + 2);

      if ($start > 1) {
        echo '<li class="page-item"><a class="page-link" href="'.pageUrl(1,$q,$active,$perPage).'">1</a></li>';
        if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
      }

      for ($i=$start; $i<=$end; $i++):
    ?>
      <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
        <a class="page-link" href="<?= pageUrl($i, $q, $active, $perPage) ?>"><?= $i ?></a>
      </li>
    <?php endfor;

      if ($end < $totalPages) {
        if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
        echo '<li class="page-item"><a class="page-link" href="'.pageUrl($totalPages,$q,$active,$perPage).'">'.$totalPages.'</a></li>';
      }
    ?>

    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= pageUrl($page+1, $q, $active, $perPage) ?>">Suivant</a>
    </li>

  </ul>
</nav>


        </div>

      </div>
    </main>

  </div>
</div>
<div class="modal fade" id="addEmployeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="../../controller/NewEmployeController.php">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter employé</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-12 col-md-6">
              <label for="add_matricule" class="form-label">Matricule</label>
              <input type="text" name="matricule" id="add_matricule" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_nom" class="form-label">Nom</label>
              <input type="text" name="nom" id="add_nom" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_prenom" class="form-label">Prénom</label>
              <input type="text" name="prenom" id="add_prenom" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_email" class="form-label">Email</label>
              <input type="email" name="email" id="add_email" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_pwd" class="form-label">Mot de passe</label>
              <input type="password" name="pwd" id="add_pwd" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_role" class="form-label">Rôle</label>
              <select name="role" id="add_role" class="form-select" required>
                <option value="employe">Employé</option>
                <option value="responsable">Responsable informatique</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_departement" class="form-label">Département</label>
              <select name="dep" id="departement" class="form-select" required>
                <option value="">-- choisi un departement --</option>
                <?php foreach($departements as $d): ?>
                  <option value="<?= (int)$d['idDep'] ?>"><?= htmlspecialchars($d['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label for="add_salle" class="form-label">Salle</label>
              <select name="salle" id="salle" class="form-select" required>
                <option value="">-- choisi une salle --</option>

              </select>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="ajouter" class="btn btn-success">Ajouter</button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="editEmployeModal<?= (int)$id ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="../../controller/UpdateEmployeController.php">
        <div class="modal-header">
          <h5 class="modal-title">Modifier employé</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" value="<?= (int)$id ?>">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Matricule</label>
              <input type="text" name="matricule" class="form-control" value="<?= $matricule ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Nom</label>
              <input type="text" name="nom" class="form-control" value="<?= $nom ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control" value="<?= $prenom ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= $email ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Mot de passe (optionnel)</label>
              <input type="password" name="pwd" class="form-control" placeholder="Laisser vide si inchangé">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Rôle</label>
              <select name="role" class="form-select" required>
                <option value="employe" <?= ($role==='employe')?'selected':'' ?>>Employé</option>
                <option value="responsable" <?= ($role==='responsable')?'selected':'' ?>>Responsable informatique</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="modifier" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>

    </div>
  </div>
</div>


<script src="../styles/js/bootstrap.bundle.min.js"></script>

<script>
  const input = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.employe-card');

  input?.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();
    cards.forEach(card => {
      const hay = card.getAttribute('data-search') || '';
      card.style.display = hay.includes(q) ? '' : 'none';
    });
  });
  document.getElementById('departement').addEventListener('change', function() {
    let depId = this.value;

    if(depId) {
        fetch('/backend/controller/getSallesController.php?id=' + depId)
        .then(response => response.json())
        .then(data => {
  let salleSelect = document.getElementById('salle');

  salleSelect.innerHTML = `<option value="">--choisi une salle--</option>`;

  data.forEach(salle => {
    salleSelect.innerHTML += `<option value="${salle.idSalle}">${salle.numSalle}</option>`;
  });
})
        .catch(error => console.error('Erreur:', error));
    }
});
</script>
    
</body>
</html>
