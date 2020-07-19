<?php


namespace Mini\Repository;


use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;
use Exception;
use Mini\Entity\UserEntity;

class UserRepository extends AbstractDbRepository {

	public function __construct() {
		parent::__construct();
		$this->entity = UserEntity::class;
	}

	/**
	 * @param $id
	 * @return UserEntity|false
	 */
	public function getById($id){
		$qb = $this->getQueryBuilder();
		$expr = $qb->expr();
		$qb->select("*")
			->from('`users`','u')
			->where($expr->eq('`id`','?'))
			->setParameter(0, $id, ParameterType::INTEGER);
		$res = $qb->execute();

		/** @var UserEntity $user */
		$user = $res->fetch();

		if($user) {
			$user->roles = $this->getRolesFromId($user->id);
		}

		return $user;
	}

	/**
	 * @param UserEntity $user
	 * @return int
	 * @throws Exception
	 */
	public function insert(UserEntity &$user) {
		$arr = $user->getArrayCopy();
		unset($arr['id']);
		unset($arr['roles']);

		$this->conn->beginTransaction();

		$qb = $this->getQueryBuilder();
		$res = $qb->insert('users')
			->values(array_fill_keys(array_keys($arr), '?'))
			->setParameters(array_values($arr))
			->execute();

		if($res!=1) {
			$this->conn->rollBack();
			return $res;
		}

		$user->id = $this->conn->lastInsertId();

		$roles = array_diff($user->roles, [UserEntity::ROLE_GUEST]);
		foreach($roles as $r) {
			$nbIns = $qb->insert('users_roles')
				->values([
					'id_user' => '?',
					'id_role' => '?'
				])
				->setParameter(0, $user->id, ParameterType::INTEGER)
				->setParameter(1, $r, ParameterType::INTEGER)
				->execute();

			if($nbIns!=1) {
				$this->conn->rollBack();
				throw new Exception("Échec de l'enregistrement de l'utilisateur");
			}
		}
	}

	/**
	 * @param UserEntity $user
	 * @return int
	 * @throws ConnectionException
	 * @throws Exception
	 */
	public function update(UserEntity $user){
		$this->conn->beginTransaction();

		$arr = $user->getArrayCopy();
		unset($arr['roles']);

		$qb = $this->getQueryBuilder();
		$qb->update('users');
		foreach($arr as $k => $v){
			$qb->set($k,$qb->createPositionalParameter($v));
		}
		$qb->where($qb->expr()->eq('id',$qb->createPositionalParameter($user->id)));

		$nbUpdt = $qb->execute();

		$oldRoles = $this->getRolesFromId($user->id);

		foreach($oldRoles as $r){
			if(!in_array($r, $user->roles)){
				$qb = $this->getQueryBuilder();
				$qb->delete('users_roles')
					->where($qb->expr()->eq('id_user', '?'))
					->andWhere($qb->expr()->eq('id_role', '?'))
					->setParameter(0, $user->id, ParameterType::INTEGER)
					->setParameter(1, $r, ParameterType::INTEGER);
				$nbUpdt += $qb->execute();
			}
		}

		foreach($user->roles as $r){
			if(!in_array($r, $oldRoles)){
				$qb = $this->getQueryBuilder();
				$qb->insert('users_roles')
					->values([
						'id_user' => '?',
						'id_role' => '?',
					])
					->setParameter(0, $user->id, ParameterType::INTEGER)
					->setParameter(1, $r, ParameterType::INTEGER);
				$nbUpdt += $qb->execute();
			}
		}

		$this->conn->commit();

		return $nbUpdt;
	}

	/**
	 * @param UserEntity $user
	 * @throws Exception
	 */
	public function delete(UserEntity $user){
		$qb = $this->getQueryBuilder();
		$qb->delete('users')
			->where(
				$qb->expr()->eq('id',$qb->createPositionalParameter($user->id))
			);
		return $qb->execute();
	}


	public function getRolesFromId(int $id){
		$qb = $this->getQueryBuilder();
		$expr = $qb->expr();

		$qb->select('id_role')
			->from('`users_roles`')
			->where($expr->eq('id_user','?'))
			->setParameter(0, $id, ParameterType::INTEGER);
		$res = $qb->execute();

		if($arr = $res->fetchAll(FetchMode::COLUMN, 0)){
			return array_map('intval', $arr);
		}
		else
			return $arr;
	}
}