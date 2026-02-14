<?php 
require_once __DIR__.'/env.php';

class Departement{
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

public function getdepartements(){
    $req=$this->pdo->prepare('select idDepartement idDep,nom,numEtage from departements');
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
public function ajouterDepartement($data){
    $req=$this->pdo->prepare('INSERT INTO departements(nom, numEtage) VALUES (?,?)');
    return $req->execute([$data['nom'],$data['numEtage']]);
}
public function supprimerDepartement($idDep){
    $req=$this->pdo->prepare('DELETE FROM departements WHERE idDepartement=?');
    return $req->execute([$idDep]);
}
public function modifierDepartement($idDep, $nom, $numEtage){
    $req=$this->pdo->prepare('UPDATE departements SET nom=?,numEtage=? WHERE idDepartement=?');
    return $req->execute([$nom,$numEtage,$idDep]);
}

}