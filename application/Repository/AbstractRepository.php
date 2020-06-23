<?php


namespace Mini\Repository;


use Mini\Core\DatabaseConnexion;
use PDO;

abstract class AbstractRepository {
	/** @var PDO */
	protected $db;

	public function __construct() {
		$this->db = DatabaseConnexion::getDb();
	}

}