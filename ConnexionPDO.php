<?php
/**
 * Classe de connexion et d'exécution des requêtes dans une BDD MySQL
 */
class ConnexionPDO {

    private $conn = null;

    /**
     * constructeur privé : connexion à la BDD
     * @param string $login
     * @param string $mdp
     * @param string $bd
     * @param string $serveur
     * @param int $port
     * @throws PDOException
     */
    public function __construct($login, $mdp, $bd, $serveur, $port){

        $dsn = "mysql:host=$serveur;dbname=$bd;port=$port;charset=utf8";
        $option = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
        try {

            $this->conn = new PDO($dsn,$login,$mdp, $option);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Exécution d'une requête de mise à jour (insert, update, delete)
     * @param string $requete
     * @param array|null $param
     * @return bool requête (booléen)
     */
    public function execute($requete, $param=null){
        try{
            $requetePrepare = $this->conn->prepare($requete);
            if($param != null){
                foreach($param as $key => &$value){
                    $requetePrepare->bindParam(":$key", $value);
                }
            }
            return $requetePrepare->execute();
        }catch(Exception $e){
            return false;
        }
    }

    /**
     * Exécution d'une requête select retournant 0 à plusieurs lignes
     * @param string $requete
     * @param array|false $param
     * @return array|false récupérées
     */
    public function query($requete, $param=null){
        try{
            $requetePrepare = $this->conn->prepare($requete);
            if($param != null){
                foreach($param as $key => &$value){
                    $requetePrepare->bindParam(":$key", $value);
                }
            }
            $reponse = $requetePrepare->execute();
            if($reponse){
                return $requetePrepare->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }
	
}