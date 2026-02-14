<?php 
require_once __DIR__.'/env.php';

class Persoone{
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
    public function getLoginInfo($email){
            $stmt = $this->pdo->prepare("SELECT id,prenom,nom,email,password,role FROM users Where email =? limit 1 ");
            $stmt->execute(([$email]));
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function ajouterPersonne($data){
        $req=$this->pdo->prepare('insert into users(matricule,nom, prenom, email, password, role, idSalle) values (?,?,?,?,?,?,?)');
        return $req->execute([$data['matricule'],$data['nom'],$data['prenom'],$data['email'],password_hash($data['pwd'],PASSWORD_DEFAULT),$data['role'],$data['salle']]);
    }
    public function desactiverEmploye($idClient){
        $req=$this->pdo->prepare('update users set isActive=0 where id=?');
        return $req->execute([$idClient]);
    }
    public function activerEmploye($idClient){
        $req=$this->pdo->prepare('update users set isActive=1 where id=?');
        return $req->execute([$idClient]);
    }
    public function getPersonneInfos($idPersonne){
        $req=$this->pdo->prepare('SELECT users.nom,prenom,email,role,password,isActive,salles.numSalle numSalle,departements.nom dep
        from users join salles on users.idSalle=salles.idSalle join departements on salles.idDepartement=departements.idDepartement where id=?');
        $req->execute([$idPersonne]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    public function updateInfos($idPersonne,$data){
        $req=$this->pdo->prepare('update users set nom=? , prenom=? , email=? , role=? where id=?');
        return $req->execute([$data['nomPersonne'],$data['prenomPersonne'],$data['email'],$data['role'],$idPersonne]);
    }
    public function getTechniciens(){
        $req=$this->pdo->prepare('select id,nom,prenom from users where role="responsable" ');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countEmployes(string $q = '', string $active = 'all'): int
{
    $where = [];
    $params = [];

    if ($q !== '') {
        $where[] = "(u.nom LIKE :q OR u.prenom LIKE :q OR u.email LIKE :q OR u.matricule LIKE :q OR dep.nom LIKE :q)";
        $params[':q'] = "%{$q}%";
    }

    if ($active === 'active') {
        $where[] = "u.isActive = 1";
    } elseif ($active === 'inactive') {
        $where[] = "u.isActive = 0";
    }

    $whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

    $sql = "
        SELECT COUNT(*) AS total
        FROM users u
        JOIN salles s ON u.idSalle = s.idSalle
        JOIN departements dep ON s.idDepartement = dep.idDepartement
        $whereSql
    ";

    $req = $this->pdo->prepare($sql);
    $req->execute($params);
    return (int)$req->fetchColumn();
}

public function getEmployesPaginated(string $q, string $active, int $limit, int $offset): array
{
    $where = [];
    $params = [];

    if ($q !== '') {
        $where[] = "(u.nom LIKE :q OR u.prenom LIKE :q OR u.email LIKE :q OR u.matricule LIKE :q OR dep.nom LIKE :q)";
        $params[':q'] = "%{$q}%";
    }

    if ($active === 'active') {
        $where[] = "u.isActive = 1";
    } elseif ($active === 'inactive') {
        $where[] = "u.isActive = 0";
    }

    $whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

    $sql = "
        SELECT
            u.id,
            u.nom AS nomEmploye,
            u.prenom,
            u.email,
            u.role,
            u.matricule,
            dep.nom AS nomDep,
            u.isActive
        FROM users u
        JOIN salles s ON u.idSalle = s.idSalle
        JOIN departements dep ON s.idDepartement = dep.idDepartement
        $whereSql
        ORDER BY u.nom, u.prenom
        LIMIT :limit OFFSET :offset
    ";

    $req = $this->pdo->prepare($sql);

    foreach ($params as $k => $v) {
        $req->bindValue($k, $v, PDO::PARAM_STR);
    }
    $req->bindValue(':limit', $limit, PDO::PARAM_INT);
    $req->bindValue(':offset', $offset, PDO::PARAM_INT);

    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

}  
