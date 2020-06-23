<?php


namespace Mini\Core;


use PDO;

class DatabaseConnexion {
	/** @var DatabaseConnexion Instance unique de PDO */
	private static $instance = null;
	/** @var PDO */
	private $db;

	/**
	 * Model constructor.
	 * Design pattern Singleton
	 */
	protected function __construct() {
		try {
			$configs = CONFIG['db'];

			if ($configs['type'] == "pgsql") {
				$databaseEncodingenc = " options='--client_encoding=" . $configs['charset'] . "'";
			} else {
				$databaseEncodingenc = "; charset=" . $configs['charset'];
			}

			$dns = $configs['type'].
				   ':host='.$configs['host'].
				   ';port='.$configs['port'].
				   ';dbname='.$configs['name'].
				   ";charset=".$configs['charset'];;

			$options = $configs['options'];

			$this->db = new PDO($dns, $configs['user'], $configs['pass'], $options);
		}
		catch(PDOException $e){
			die('Connexion à la base de données impossible.<br> Erreur : '. $e->getMessage());
		}
	}

	public static function getDb(){
		if(!isset(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance->db;
	}
}