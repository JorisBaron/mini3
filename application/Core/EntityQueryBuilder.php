<?php


namespace Mini\Core;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Mini\Entity\BaseEntity;
use PDO;

class EntityQueryBuilder extends \Doctrine\DBAL\Query\QueryBuilder {
	protected $entity = BaseEntity::class;
	protected $constructorArgs  = null;

	public function __construct(Connection $connection, string $entity = BaseEntity::class, array $constructorArgs = null) {
		parent::__construct($connection);
		$this->entity = $entity;
		$this->constructorArgs   = $constructorArgs;
	}



	public function execute() {
		$stmt = parent::execute();
		if ($this->getType() === self::SELECT) {
			$stmt->setFetchMode(FetchMode::CUSTOM_OBJECT | PDO::FETCH_PROPS_LATE, $this->entity, $this->constructorArgs);
		}
		return $stmt;
	}
}