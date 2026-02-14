<?php 
require_once __DIR__.'/env.php';
class Affectation{
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


public function affecterMateriel($data){
    $req=$this->pdo->prepare('insert into affectations (dateAffectation,idMateriel,idSalle) values (?,?, ?)');
    return $req->execute([$data['dateAffectation'],$data['idMateriel'],$data['salle']]);
}
}