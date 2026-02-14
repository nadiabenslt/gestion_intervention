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
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choisir le rôle</title>
<link rel="icon" type="image/jpg" href="./images/logoAUL.jpg">

    <link href="./styles/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .role-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .logo {
            max-width: 180px;
        }
    </style>
</head>
<body>

<div class="container vh-100 d-flex flex-column justify-content-center align-items-center">

    <div class="mb-5 text-center">
        <img src="./images/logo-removebg-preview.png" alt="Logo Agence" class="logo mb-3">
        <h4 class="fw-bold">Système de Gestion des Interventions</h4>
        <p class="text-muted">Choisissez votre interface</p>
    </div>

    <div class="row g-4 w-100 justify-content-center">

        <div class="col-md-4">
            <a href="responsable/index.php" class="text-decoration-none text-dark">
                <div class="card role-card text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Continuer comme Admin</h5>
                        <p class="card-text text-muted">
                            Gestion du système, statistiques et supervision.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="technicien/index.php" class="text-decoration-none text-dark">
                <div class="card role-card text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-tools fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Continuer comme Technicien</h5>
                        <p class="card-text text-muted">
                            Suivi et traitement des interventions.
                        </p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
