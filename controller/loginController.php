<?php 
session_start();

require_once __DIR__.'/../model/persoone.php';
$personne=new Persoone();
if(isset($_POST["conn"])){

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $user=$personne->getLoginInfo($email);
if ($user && password_verify($pwd,$user['password'])) {
    $_SESSION['personne']=[
        'idP'=>$user['id'],
        'email'=>$user['email'],
        'nom'=>$user['nom'],
        'prenom'=>$user['prenom'],
        'role'=>$user['role']
    ];

    if(isset($_POST['remember'])){
        setcookie("role", $user['role'], time() + 86400);
    }
    switch($user['role']){
        case 'responsable':
            header('Location: ../view/chooseRole.php');
            exit();

        case 'employe':
            header('Location: ../view/employe/index.php');
            exit();
    }
} else {
    $_SESSION['failed']='email ou mot de passe incorrecte!';
    header('Location: ../view/index.php');
    exit();
    }}
