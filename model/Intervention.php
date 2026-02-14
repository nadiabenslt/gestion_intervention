<?php 
require_once __DIR__.'/env.php';

class Intervention{
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
public function affecterDemande($idDemande,$idTechnicien){
    $req=$this->pdo->prepare('insert into interventions (idDemande,idTechnicien) values (?,?)');
    return $req->execute([$idDemande,$idTechnicien]);
}
public function commencerIntervention($idIntervention){
    $req=$this->pdo->prepare('UPDATE interventions set dateDebut=now() where idIntervention=?');
    return $req->execute([$idIntervention]);
}
public function getInterventions($idTechnicien){
    $sql = "
        SELECT
            i.idIntervention,
            di.idDemandeIn,
            di.description,
            di.lieuMateriel,
            di.priorite,
            di.etatDemande,

            tm.libelleTypeMateriel typeM,
            m.libelleMarque marqueM,
            i.dateDebut,
            i.dateFin,

            TIMESTAMPDIFF(MINUTE, di.dateDemande, COALESCE(i.dateFin, NOW())) AS delaiMinutes,
            ROUND(TIMESTAMPDIFF(MINUTE, di.dateDemande, COALESCE(i.dateFin, NOW()))/60, 2) AS delaiHeures,
            CASE
                WHEN TIMESTAMPDIFF(HOUR, di.dateDemande, COALESCE(i.dateFin, NOW())) > 120 THEN 1
                ELSE 0
            END AS isOverdue

        FROM interventions i
        JOIN demandesInterventions di ON i.idDemande = di.idDemandeIn
        JOIN materiels mat ON di.idMateriel = mat.idMateriel
        JOIN typemateriels tm ON tm.idTypeMateriel = mat.idTypeMateriel
        JOIN marques m ON mat.idMarque = m.idMarque

        WHERE i.idTechnicien = ? 
ORDER BY
    CASE
      WHEN di.etatDemande = 'en cours' THEN 1
      WHEN di.etatDemande = 'affectée' THEN 2
      WHEN di.etatDemande = 'terminée' THEN 3
      ELSE 4
    END,
    di.dateDemande ASC    ";

    $req = $this->pdo->prepare($sql);
    $req->execute([$idTechnicien]);
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
public function remplirFicheIntervention($idIntervention,$data){
    $req=$this->pdo->prepare('update interventions set typeIntervention=?, dateFin=?, action=? where idIntervention=?');
    return $req->execute([$data['typeIntervention'],$data['dateFin'],$data['action'],$idIntervention]);
}
public function  getInterventionById($idIntervention) {
    $req=$this->pdo->prepare('SELECT idIntervention,typeIntervention,dateDebut,dateFin,action,nom,prenom FROM interventions JOIN users on interventions.idTechnicien=users.id WHERE idIntervention=?');
    $req->execute([$idIntervention]);
    return $req->fetch(PDO::FETCH_ASSOC);
}
public function  typesInterventions() {
    $req=$this->pdo->prepare('SELECT typeIntervention FROM typesinterventions');
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
 public function statsParClientPeriode($dateDebut, $dateFin): array
    {
        $sql = "
            SELECT CONCAT(u.prenom,' ',u.nom) AS label, COUNT(di.idDemandeIn) AS total
            FROM demandesinterventions di
            JOIN users u ON di.idClient = u.id
            WHERE di.dateDemande BETWEEN :d1 AND :d2
            GROUP BY u.id, u.prenom, u.nom
            ORDER BY total DESC
            LIMIT 10
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':d1' => $dateDebut, ':d2' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }



    public function statsParEquipementEtType($dateDebut, $dateFin){
        $sqlEquip = "
            SELECT mat.numSerie AS label, COUNT(di.idDemandeIn) AS total
            FROM demandesinterventions di
            JOIN materiels mat ON di.idMateriel = mat.idMateriel
            WHERE di.dateDemande BETWEEN :d1 AND :d2
            GROUP BY mat.idMateriel, mat.numSerie
            ORDER BY total DESC
            LIMIT 5
        ";
        $stmt1 = $this->pdo->prepare($sqlEquip);
        $stmt1->execute([':d1' => $dateDebut, ':d2' => $dateFin]);
        $equip = $stmt1->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $sqlType = "
            SELECT tm.libelleTypeMateriel AS label, COUNT(di.idDemandeIn) AS total
            FROM demandesinterventions di
            JOIN materiels mat ON di.idMateriel = mat.idMateriel
            JOIN typemateriels tm ON tm.idTypeMateriel = mat.idTypeMateriel
            WHERE di.dateDemande BETWEEN :d1 AND :d2
            GROUP BY tm.idTypeMateriel, tm.libelleTypeMateriel
            ORDER BY total DESC
        ";
        $stmt2 = $this->pdo->prepare($sqlType);
        $stmt2->execute([':d1' => $dateDebut, ':d2' => $dateFin]);
        $types = $stmt2->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return ['equip' => $equip, 'types' => $types];
    }

public function getDiagnostics(){
    $req=$this->pdo->prepare('SELECT i.idIntervention,i.typeIntervention,i.dateDebut,i.dateFin,i.action,u.nom,u.prenom,di.etatDemande from interventions i JOIN users u on i.idTechnicien=u.id join demandesinterventions di on i.idDemande=di.idDemandeIn where di.etatDemande="terminée"');
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
public function historiques(){
    $req=$this->pdo->prepare("
    SELECT 
    i.idIntervention as idDemandeIn,
    CONCAT(u.nom, ' ', u.prenom) AS demandeur,
    tm.libelleTypeMateriel AS materiel,
    m.numSerie AS numSerie,
    d.lieuMateriel AS lieuMateriel,
    d.description,
    d.dateDemande,
    i.typeIntervention,
    i.dateDebut,
    i.dateFin,
    i.action AS action,
    CONCAT(tech.nom, ' ', tech.prenom) AS technicien
FROM demandesinterventions d
JOIN interventions i ON d.idDemandeIn = i.idDemande
JOIN users u ON d.idClient = u.id
JOIN materiels m ON d.idMateriel = m.idMateriel
JOIN typemateriels tm ON m.idTypeMateriel = tm.idTypeMateriel
JOIN users tech ON i.idTechnicien = tech.id
WHERE d.etatDemande = 'terminée'
ORDER BY i.dateFin DESC;
    ");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
}
