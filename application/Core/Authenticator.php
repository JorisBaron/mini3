<?php


namespace Mini\Core;


use Mini\Entity\UserEntity;

class Authenticator {
	public function __construct() {
		if(session_status() == PHP_SESSION_NONE) {
			session_set_cookie_params(['path'=> URL_SUB_FOLDER, 'httponly'=>true]);
			session_start();
		}
	}

	public function checkTimeout(){
		if(isset($_SESSION['login']['time']) && $_SESSION['login']['time'] + CONFIG['session']['timeout'] < time()){
			$this->logout();
		}
	}

	/**
	 * @param int $userId
	 */
	public function login($userId) {
		$_SESSION['login'] = [
			'time' => time(),
			'id' => $userId,
		];
	}


	public function logout(){
		unset($_SESSION['login']);
	}

	/**
	 * @return UserEntity|null
	 */
	public function getUserId(){
		$this->checkTimeout();
		return $_SESSION['login']['id'] ?? null;
	}
}