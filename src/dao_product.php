<?php			// dao_user.php
class Product {
	const TABLE_NAME = "produit";
	static $pdo;
	 public static function connect() {		
		 if (!isset($pdo) ){
			$hote = 'localhost';
			$nom_bdd = 'formation';
			$utilisateur = 'root';
			$mot_de_passe ='';
			self::$pdo = new PDO('mysql:host='.$hote.';dbname='.$nom_bdd, $utilisateur, $mot_de_passe, 
								array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 }
		 return self::$pdo;
	 }
	
	 public static function find($idProduct) {		
		if ($pdo = self::connect()) {
			$requete = $pdo->prepare("SELECT * FROM ".self::TABLE_NAME .",fournisseur WHERE `id_produit` = :id and fournisseur.id_fournisseur=produit.id_fournisseur");
			$requete->bindParam(':id', $idProduct);	
			$requete->execute();
			$resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
			return $resultats;
		}
		return null;	 
	}
	
	
	 public static function findAll() {		
		if ($pdo = self::connect()) {
			$requete = $pdo->prepare("SELECT produit.`id_produit`, produit.`libelle`, produit.`prix_unitaire`, produit.`reference`,fournisseur.nom as nomfournisseur FROM produit,fournisseur WHERE fournisseur.id_fournisseur=produit.id_fournisseur" );
			$requete->execute();
			$resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
			return $resultats;
		}
		return null;
	 }


	 public static function add($product) {		
		if ($pdo = self::connect()) {
			$sql = "INSERT INTO ". self::TABLE_NAME  
					."(libelle, prix_unitaire, reference,id_fournisseur)" 
					."VALUES (:libelle, :prix_unitaire, :reference, :id_fournisseur)";
			$stmt= $pdo->prepare($sql);
			$stmt->execute($product);
			$idProduct=$pdo->lastInsertId();
			return self::find($idProduct);
		}
		return null;	 
	}
	 public static function update($product,$idProduct) {			
		
		if (($pdo = self::connect()) && !empty($product) && !empty($idProduct)) {	
		    $requete = $pdo->prepare("UPDATE ". self::TABLE_NAME ." SET libelle=:libelle, prix_unitaire=:prix_unitaire,reference=:reference,id_fournisseur=:id_fournisseur WHERE id_produit=:id" );
		   $requete->execute(array('libelle' => $product['libelle'],
		                          'prix_unitaire' =>  $product['prix_unitaire'],
		                          'reference' =>  $product['reference'],
		                          'id_fournisseur' =>  $product['id_fournisseur'],
								   'id' => $idProduct
								   ));
		   return self::find($idProduct);
		}
		return null;	 
	}
	
	public static function delete($idProduct) {		
		if (($pdo = self::connect()) && !empty($idProduct)) {
			$sql = "DELETE FROM ".self::TABLE_NAME ." WHERE `id_produit` = $idProduct";
			return $pdo->exec($sql);  // returns 0 or 1
		}
		return false;
	}
   
	public static function Fournisseurs(){
		if ($pdo = self::connect()) {
			$requete = $pdo->prepare("SELECT * FROM fournisseur" );
			$requete->execute();
			$resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
			return $resultats;
		}
		return null;
	}
}

function testDaoInsert(){
  $newUser =	array("nom"=>"Peltier","prenom"=>"Augustin","login"=>"newLogin2","password"=>"unPawd","email"=>"pa3@free.fr","profil"=>1);
  try{
	  $insertedUser = DaoUser::add($newUser);
	  var_dump($insertedUser);
	} catch (Exception $e) {
			echo "Echec ". $e->getMessage();
	}			  
}
function testDaoUpdate(){
  $aUser = array("id_user"=> 13,"nom"=>"Peltier","prenom"=>"Augustin","login"=>"newLogin2","password"=>"unPawd","email"=>"pa2@free.fr","profil"=>1);
  try{
	  $updatedUser = DaoUser::update($aUser);
	  var_dump($updatedUser);
	} catch (Exception $e) {
			echo "Echec ". $e->getMessage();
	}			  
}

function testDaoDelete(){
  $idUser = 13;
  try{
	  DaoUser::delete($idUser);
	} catch (Exception $e) {
			echo "Echec ". $e->getMessage();
	}			  
}

//testDaoInsert();
//testDaoUpdate();
//testDaoDelete();