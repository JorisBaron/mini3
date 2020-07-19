<?php


namespace Mini\Repository;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\FetchMode;
use Mini\Core\EntityQueryBuilder;
use Mini\Entity\BaseEntity;

abstract class AbstractDbRepository {
	/** @var Connection */
	protected $conn;

	protected $entity = BaseEntity::class;

	public function __construct() {
		$this->conn = DriverManager::getConnection(CONFIG['db']);
		//$this->conn->setFetchMode(FetchMode::CUSTOM_OBJECT);
	}

	public function getQueryBuilder() : EntityQueryBuilder{
		return new EntityQueryBuilder($this->conn, $this->entity, null);
	}
}