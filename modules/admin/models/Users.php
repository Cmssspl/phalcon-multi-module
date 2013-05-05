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

	public function initialize() {
		$this->skipAttributes(array(
			'isActive',
			'isBlock',
			'isAdmin',
			'timeLastLogin'
		));
	}

	public function password($password) {
		return sha1($password);
	}

	public function login($nick = false, $pass = false) {
		if(empty($nick)) {
			$nick = $this->nick;
		}

		if(empty($pass)) {
			$pass = $this->pass;
		}

		$result = $this->findFirst(array(
			'nick = :nick: AND pass = :pass:',
			'bind' => array(
				'nick' => $nick,
				'pass' => $pass
			)
		));

		return $result;
	}

	public function isActive() {
		if($this->isActive) {
			return true;
		} else {
			return false;
		}
	}

	public function isBlock() {
		if($this->isBlock) {
			return true;
		} else {
			return false;
		}
	}

	public function checkNick($nick = false) {
		if(empty($nick)) {
			$nick = $this->nick;
		}

		$result = $this->findFirst(array(
			'nick = :nick:',
			'bind' => array(
				'nick' => $nick
			)
		));

		if(empty($result)) {
			return true;
		} else {
			return false;
		}
	}

	public function checkEmail($email = false) {
		if(empty($email)) {
			$email = $this->email;
		}

		$result = $this->findFirst(array(
			'email = :email:',
			'bind' => array(
				'email' => $email
			)
		));

		if(empty($result)) {
			return true;
		} else {
			return false;
		}
	}

	public function registry() {
		$this->timeRegistry = time();

		$result = $this->create();

		return $result;
	}

	public function setPass($pass) {
		$this->pass = $this->password($pass);
	}
}