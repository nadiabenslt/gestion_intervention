<?php 
require_once __DIR__.'/env.php';

class TypeMateriel{
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
public function getTypeMateriel(){
    $req=$this->pdo->prepare('select idTypeMateriel,libelleTypeMateriel from typeMateriels');
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
}