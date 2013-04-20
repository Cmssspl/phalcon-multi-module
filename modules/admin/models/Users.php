<?php

namespace Admin\Models;

class Users extends \Phalcon\Mvc\Model {
//	public $id;
//	public $nick;
//	public $password;
//	public $email;
//	public $active;
//	public $block;
//	public $timeRegistry;

	public function password($password) {
		return sha1($password);
	}

	public function login($nick, $password) {
		$result = $this->findFirst(array(
			'nick = ( :nick: OR email = :nick: ) AND password = :password: AND active = 1',
			'bind' => array(
				'nick' => $nick,
				'password' => $this->password($password)
			)
		));

		$usersHistoryModel = new UsersHistory();
		$usersHistoryModel->log();

//		if(!empty($result)) {
//			$loginHistoryModel = new LoginHistory();
//			$loginHistoryModel->
//		}

		return $result;
	}
}