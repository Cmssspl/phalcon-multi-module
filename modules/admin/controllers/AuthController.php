<?php

namespace Admin\Controllers;

class AuthController extends \Phalcon\Mvc\Controller
{
	private function _registerSession($user)
	{
		$this->session->set('auth', array(
			'id' => $user->id,
			'name' => $user->name
		));
	}

	public function loginAction() {
		echo 'LOGOWANIE';
	}

	public function logoutAction()
	{

	}
}