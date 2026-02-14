<?php 
require_once __DIR__.'/env.php';

class Panne{
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
    public function declarerDemande($idPersonne,$data){
        $req=$this->pdo->prepare('insert into demandesInterventions (description,lieuMateriel,priorite, idMateriel, idClient) values (?,?,?,?,?)');
        return $req->execute([$data['description'],$data['lieuMateriel'],$data['priorite'],$data['idMateriel'],$idPersonne]);
    }
    public function getdemandesInterventions($idPersonne){
        $req=$this->pdo->prepare('SELECT idDemandeIn,libelleTypeMateriel,libellemarque,dateDemande,description,etatDemande,priorite FROM demandesInterventions join materiels on demandesInterventions.idMateriel=materiels.idMateriel join typemateriels on materiels.idTypeMateriel=typemateriels.idTypeMateriel join marques on materiels.idMarque=marques.idMarque where idClient=?
');
        $req->execute([$idPersonne]);
        return $req->fetchAll( PDO::FETCH_ASSOC);
    }
    public function annulerDemande($idPanne,){
        $req=$this->pdo->prepare('update demandesInterventions set etatDemande="annulée" where idDemandeIn=?');
        return $req->execute([$idPanne]);
    }
    public function supprimerDemande($idPanne,){
        $req=$this->pdo->prepare('delete from demandesInterventions where idDemandeIn=?');
        return $req->execute([$idPanne]);
    }
    public function getPanneById($idPanne){
        $req=$this->pdo->prepare('SELECT idDemandeIn,demandesInterventions.idMateriel idMateriel,etatDemande,users.nom nom,
    users.prenom prenom,
    materiels.numSerie numero,
    tm.libelleTypeMateriel typeM,
    mq.libelleMarque marque,
    salles.numSalle numSalle,
    departements.nom dep,
    dateDemande,description,etatDemande,priorite FROM demandesInterventions JOIN users on demandesInterventions.idClient=users.id join materiels on demandesInterventions.idMateriel=materiels.idMateriel JOIN typemateriels tm on materiels.idTypeMateriel=tm.idTypeMateriel JOIN marques mq on materiels.idMarque=mq.idMarque JOIN salles on users.idSalle=salles.idSalle JOIN departements on salles.idDepartement=departements.idDepartement WHERE  idDemandeIn=?');
        $req->execute([$idPanne]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    public function getAlldemandesInterventions(){
        $req=$this->pdo->prepare('select idDemandeIn,description,dateDemande,libelleTypeMateriel,libelleMarque,etatDemande,lieuMateriel from demandesInterventions join materiels on demandesInterventions.idMateriel=materiels.idMateriel join typemateriels on typemateriels.idTypeMateriel=materiels.idTypeMateriel join marques on materiels.idMarque=marques.idMarque join affectations on materiels.idMateriel=affectations.idMateriel');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifierEtatDemande($idDemande,$etat){
         $req=$this->pdo->prepare('update demandesinterventions set etatDemande=? where idDemandeIn=?');
        return $req->execute([$etat,$idDemande]);
    }
        public function getEtatsDemandes($idTechnicien){
        $req=$this->pdo->prepare('SELECT COUNT(idDemandeIn) as total,etatDemande from demandesinterventions di JOIN interventions on di.idDemandeIn=interventions.idDemande where interventions.idTechnicien=?  GROUP BY di.etatDemande');
        $req->execute([$idTechnicien]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
public function calculerDuree() {
    $req = $this->pdo->prepare("
        SELECT d.*,i.idIntervention,libelleTypeMateriel,
        libelleMarque,i.dateDebut,i.dateFin,TIMESTAMPDIFF(MINUTE, 
        d.dateDemande, COALESCE(i.dateFin, NOW())) AS delaiMinutes,
        ROUND(TIMESTAMPDIFF(MINUTE, d.dateDemande, COALESCE(i.dateFin, NOW()))/60, 2) AS delaiHeures,
         CASE WHEN TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 120 THEN 1 ELSE 0 END AS isOverdue FROM demandesinterventions d LEFT JOIN interventions i ON i.idDemande = d.idDemandeIn 
         join materiels on d.idMateriel=materiels.idMateriel join typemateriels on typemateriels.idTypeMateriel=materiels.idTypeMateriel join marques on materiels.idMarque=marques.idMarque
        ORDER BY d.dateDemande DESC 
    ");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

public function redeclarerDemande($idDemande){
    $req=$this->pdo->prepare('update demandesinterventions set etatDemande="en attente",dateDemande=now() where idDemandeIn=?');
        return $req->execute([$idDemande]);
}
public function getKpis(): array {
        $sql = "
            SELECT
              COUNT(*) AS total,
              SUM(CASE WHEN d.etatDemande = 'en attente' THEN 1 ELSE 0 END) AS attente,
              SUM(CASE WHEN d.etatDemande = 'affectée' THEN 1 ELSE 0 END) AS affecter,
              SUM(CASE WHEN d.etatDemande = 'en cours' THEN 1 ELSE 0 END) AS encours,
              SUM(CASE WHEN d.etatDemande = 'terminée' THEN 1 ELSE 0 END) AS cloturee,

              SUM(CASE WHEN TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 120 THEN 1 ELSE 0 END) AS retard,
              SUM(CASE WHEN TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) >= 96
                        AND TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) <= 120
                       THEN 1 ELSE 0 END) AS arisque
            FROM demandesinterventions d
            LEFT JOIN interventions i ON i.idDemande = d.idDemandeIn where d.etatDemande != 'annulée'
        ";
        $req = $this->pdo->query($sql);
        return $req->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getPriorites(int $limit = 15): array {
        $sql = "
            SELECT d.idDemandeIn,
              d.dateDemande,
              d.lieuMateriel,
              d.etatDemande,
              d.priorite,
              ROUND(TIMESTAMPDIFF(MINUTE, d.dateDemande, COALESCE(i.dateFin, NOW()))/60, 2) AS delaiHeures,
              CASE WHEN TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 120 THEN 1 ELSE 0 END AS isOverdue,

              m.numSerie,
              tm.libelleTypeMateriel,
              mk.libelleMarque,
              dep.nom AS depNom,
              s.numSalle

            FROM demandesinterventions d
            LEFT JOIN interventions i ON i.idDemande = d.idDemandeIn
            LEFT JOIN materiels m ON m.idMateriel = d.idMateriel
            LEFT JOIN typemateriels tm ON tm.idTypeMateriel = m.idTypeMateriel
            LEFT JOIN marques mk ON mk.idMarque = m.idMarque
            LEFT JOIN users u ON u.id = d.idClient
            LEFT JOIN salles s ON s.idSalle = u.idSalle
            LEFT JOIN departements dep ON dep.idDepartement = s.idDepartement

            WHERE d.etatDemande <> 'cloturée'
              AND (
                TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 120
                OR TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) >= 96
              )

            ORDER BY
              isOverdue DESC,
              delaiHeures DESC
            LIMIT :lim
        ";
        $req = $this->pdo->prepare($sql);
        $req->bindValue(':lim', $limit, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDemandesEnRetard() {
    $req = $this->pdo->prepare("
        SELECT d.*, i.idIntervention, libelleTypeMateriel, libelleMarque,
               TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) AS delaiHeures
        FROM demandesinterventions d 
        LEFT JOIN interventions i ON i.idDemande = d.idDemandeIn 
        JOIN materiels ON d.idMateriel = materiels.idMateriel 
        JOIN typemateriels ON typemateriels.idTypeMateriel = materiels.idTypeMateriel 
        JOIN marques ON materiels.idMarque = marques.idMarque
        WHERE d.etatDemande NOT IN ('cloturée', 'terminée', 'annulée')
        AND TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 96
        ORDER BY d.dateDemande DESC
    ");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

    public function searchDemandes(array $filters): array {
        $q = trim($filters['q'] ?? '');
        $etat = trim($filters['etat'] ?? '');
        $sla = trim($filters['filter_sla'] ?? '');
        $dep = (int)($filters['dep'] ?? 0);
        $tech = (int)($filters['tech'] ?? 0);

        $where = [];
        $params = [];

        if ($etat !== '') {
            $where[] = "d.etatDemande = :etat";
            $params[':etat'] = $etat;
        }

        if ($sla === 'retard') {
            $where[] = "TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 120";
        } elseif ($sla === 'risque') {
            $where[] = "TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) >= 96
                        AND TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) <= 120";
        }

        if ($dep > 0) {
            $where[] = "dep.idDep = :dep";
            $params[':dep'] = $dep;
        }

        if ($tech > 0) {
            $where[] = "i.idTechnicien = :tech";
            $params[':tech'] = $tech;
        }

        if ($q !== '') {
            $where[] = "(u.matricule LIKE :q OR u.email LIKE :q OR u.nom LIKE :q OR u.prenom LIKE :q
                        OR m.numSerie LIKE :q OR s.numSalle LIKE :q OR dep.nom LIKE :q)";
            $params[':q'] = "%{$q}%";
        }

        $whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

        $sql = "
            SELECT
              d.idDemandeIn,
              d.dateDemande,
              d.etatDemande,
              d.priorite,
              ROUND(TIMESTAMPDIFF(MINUTE, d.dateDemande, COALESCE(i.dateFin, NOW()))/60, 2) AS delaiHeures,
              CASE WHEN TIMESTAMPDIFF(HOUR, d.dateDemande, COALESCE(i.dateFin, NOW())) > 120 THEN 1 ELSE 0 END AS isOverdue,

              m.numSerie,
              tm.libelleTypeMateriel,
              mk.libelleMarque,
              dep.nom AS depNom,
              s.numSalle,
              CONCAT(u.nom,' ',u.prenom) AS demandeur

            FROM demandesinterventions d
            LEFT JOIN interventions i ON i.idDemande = d.idDemandeIn
            LEFT JOIN materiels m ON m.idMateriel = d.idMateriel
            LEFT JOIN typemateriels tm ON tm.idTypeMateriel = m.idTypeMateriel
            LEFT JOIN marques mk ON mk.idMarque = m.idMarque
            LEFT JOIN users u ON u.id = d.idClient
            LEFT JOIN salles s ON s.idSalle = u.idSalle
            LEFT JOIN departements dep ON dep.idDepartement = s.idDepartement

            $whereSql
            ORDER BY d.dateDemande DESC
            LIMIT 200
        ";

        $req = $this->pdo->prepare($sql);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function statsParDepartement($start, $end) {
    $sql = "
        SELECT dep.nom AS dep, COUNT(*) AS total
        FROM demandesinterventions d
        LEFT JOIN users u ON u.id = d.idClient
        LEFT JOIN salles s ON s.idSalle = u.idSalle
        LEFT JOIN departements dep ON dep.idDepartement = s.idDepartement
        WHERE d.dateDemande BETWEEN :start AND :end
        GROUP BY dep.nom
        ORDER BY total DESC
        LIMIT 10
    ";
    $req = $this->pdo->prepare($sql);
    $req->bindValue(':start', $start);
    $req->bindValue(':end', $end . ' 23:59:59'); // Pour inclure toute la journée de fin
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
    public function statsParJour(int $days = 7): array {
        $sql = "
            SELECT DATE(d.dateDemande) AS day, COUNT(*) AS total
            FROM demandesinterventions d
            WHERE d.dateDemande >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY DATE(d.dateDemande)
            ORDER BY day ASC
        ";
        $req = $this->pdo->prepare($sql);
        $req->bindValue(':days', $days, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

  public function statsDepartementsByPeriod(string $start, string $end, int $depId = 0): array {
    $sql = "
      SELECT dep.nom AS departement, COUNT(*) AS total
FROM demandesinterventions d
JOIN users u        ON d.idClient = u.id
JOIN salles s       ON s.idSalle = u.idSalle
JOIN departements dep ON dep.idDepartement = s.idDepartement
WHERE d.dateDemande BETWEEN :start AND :end
  AND (:depId = 0 OR dep.idDepartement = :depId)
GROUP BY dep.idDepartement, dep.nom
ORDER BY total DESC;

    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      ':start' => $start . " 00:00:00",
      ':end'   => $end   . " 23:59:59",
      ':depId' => $depId,
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function statsParDep(string $start, string $end, int $depId = 0): array {
  return $this->statsDepartementsByPeriod($start, $end, $depId);
}

}




