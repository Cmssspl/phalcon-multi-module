<?php

namespace Admin\Controllers;

use Admin\Forms\LoginForm;
use Admin\Models\Users;

class AuthController extends \Phalcon\Mvc\Controller
{
	private function _registerSession($user)
	{
		$this->session->set('auth', array(
			'id' 	=> $user->id,
			'name'	=> $user->name
		));
	}

	public function loginAction() {
		$loginForm = new LoginForm();

		$this->view->setVar('loginForm', $loginForm);
	}

	public function logoutAction() {
		echo 'WYLOGOWYWANIE';
	}

	private function getFormLogin() {

	}
}