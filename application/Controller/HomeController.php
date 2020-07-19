<?php

namespace Mini\Controller;

use Mini\Entity\RoleEntity;
use Mini\Entity\UserEntity;
use Mini\Repository\RoleRepository;
use Mini\Repository\UserRepository;

class HomeController extends AbstractController {
	public function index() {
		$repoUser = new UserRepository();
/*
		var_dump($this->app->user);

		$u = $repoUser->getById(1);
		var_dump($u);

		$u->addRole(2);
		var_dump($u);
		$repoUser->update($u);
*/
		$u = new UserEntity();
		$u->username = 'test';
		$u->password = 'pass';
		$u->roles = [UserEntity::ROLE_ADMIN];
		$repoUser->insert($u);

		var_dump($repoUser->getById($u->id));

		$u->username = 'slt';
		$u->addRole(3);
		$u->removeRole(UserEntity::ROLE_ADMIN);
		$repoUser->update($u);

		var_dump($repoUser->getById($u->id));

		$repoUser->delete($u);

		var_dump($repoUser->getById($u->id));
		/*$repoUser->updateRole($u);
		$u->addRole($repoRole->getById(RoleEntity::ROLE_USER));
		$repoUser->updateRole($u);*/

		//$u = new UserEntity();

		return [
			'user' => $u
		];
	}
}
