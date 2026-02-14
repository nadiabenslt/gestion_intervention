<?php
session_start();

if (isset($_SESSION["personne"])) {
    switch ($_SESSION['personne']['role']) {
        case 'responsable':
            header('Location: ./responsable/index.php');
            exit;
        case 'employe':
            header('Location: ./employe/index.php');
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
<link rel="icon" type="image/png" href="./images/logoAUL.jpg">
    <link href="./styles/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
<div class="card shadow-lg border-0 rounded-4" style="max-width: 420px; width: 100%;">
            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-3">
                    <img
                        src="./images/logo-removebg-preview.png"
                        alt="Logo Agence"
                        style="height: 70px; width: auto;"
                        class="img-fluid"
                    >
                </div>
                <?php if(isset($_SESSION['failed'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['failed']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['failed']); ?>
                <?php endif; ?>
                <h4 class="text-center mb-4">Se connecter</h4>

                <form method="post" action="../controller/loginController.php">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="ex: nom@exemple.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="pwd" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" name="conn" class="btn btn-primary w-100 py-2">
                        Se connecter
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
