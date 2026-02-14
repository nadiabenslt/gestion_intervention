<?php 
require_once __DIR__.'/env.php';

class Materiel{
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
public function ajouterMateriel($data){
    $req=$this->pdo->prepare('INSERT INTO materiels (numSerie, idTypeMateriel, idMarque, dateAchat, prix, caracteristiques) VALUES (?,?,?,?,?,?)');
    return $req->execute([$data['numSerie'],$data['typeM'],$data['marque'],$data['dateAchat'],$data['prixAchat'],$data['caracteristique']]);
}
public function getMateriels(){
    $req=$this->pdo->prepare('select m.idMateriel,numSerie,libelleTypeMateriel,libelleMarque,dateAchat,prix,caracteristiques,COALESCE(s.numSalle, "affecter") as salle from materiels m join typeMateriels tm on m.idTypeMateriel=tm.idTypeMateriel join marques on m.idMarque=marques.idMarque LEFT JOIN affectations a ON m.idMateriel = a.idMateriel LEFT JOIN salles s ON a.idSalle = s.idSalle ');
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
public function modifierMateriel($idMateriel,$data){
    $req=$this->pdo->prepare('update materiels set numSerie=?,idTypeMateriel=?,idMarque=?,dateAchat=?,prix=?,caracteristiques=? where idMateriel=?');
    return $req->execute([$data['numSerie'],$data['typeM'],$data['marque'],$data['dateAchat'],$data['prixAchat'],$data['caracteristique'],$idMateriel]);
}
public function supprimerMateriel($idMateriel){
    $req=$this->pdo->prepare('delete from materiels where idMateriel=?');
    return $req->execute([$idMateriel]);
}
public function getMaterielById($idMateriel){
    $req=$this->pdo->prepare('select idMateriel,numSerie,libelleTypeMateriel,libelleMarque,dateAchat,prix,caracteristiques from materiels m join typeMateriels tm on m.idTypeMateriel=tm.idTypeMateriel join marques on m.idMarque=marques.idMarque where idMateriel=?');
    $req->execute([$idMateriel]);
    return $req->fetch(PDO::FETCH_ASSOC);
}
public function getLocationMateriel($idMateriel){
    $req=$this->pdo->prepare('select numSerie,a.idSalle,d.nom,d.nom,d.numEtage from materiels join typemateriels on materiels.idTypeMateriel=typemateriels.idTypeMateriel join affectations a on materiels.idMateriel=a.idMateriel join salles on a.idSalle=salles.idSalle join departements d on salles.idDepartement=d.idDepartement WHERE materiels.idMateriel=?');
    $req->execute([$idMateriel]);
    return $req->fetch(PDO::FETCH_ASSOC);
}
public function getMaterielByPersonne($idPersonne){
        $req=$this->pdo->prepare('SELECT materiels.idMateriel,numSerie,tm.libelleTypeMateriel typeM,m.libelleMarque marqueM,s.numSalle from materiels
         join typemateriels tm on materiels.idTypeMateriel=tm.idTypeMateriel 
         join marques m on materiels.idMarque=m.idMarque 
         join affectations a on materiels.idMateriel=a.idMateriel 
         join salles s on s.idSalle=a.idSalle
         JOIN users p on s.idSalle=p.idSalle 
         WHERE p.id=?');
        $req->execute([$idPersonne]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}