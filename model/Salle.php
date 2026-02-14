<?php 
require_once __DIR__.'/env.php';

class Salle{
    public $pdo;
public function __construct()
{
    $host = $_ENV['DB_HOST'];
    $db   = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];
    try{
        $this->pdo=new PDO("mysql:host=$host;dbname=$db",$user,$pass);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
        echo $e->getMessage();
    }
}

public function getSalles($idDep){
    $req=$this->pdo->prepare('select idSalle,numSalle from salles where idDepartement=?');
    $req->execute([$idDep]);
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
public function getSallesEmploye($idDep){
    $req=$this->pdo->prepare('select * from salles LEFT join users on users.idSalle=salles.idSalle where users.idSalle is null and idDepartement=?');
    $req->execute([$idDep]);
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
}