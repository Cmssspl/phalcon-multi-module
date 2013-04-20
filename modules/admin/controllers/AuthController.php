<?php

namespace Admin\Controllers;

use Admin\Forms\LoginForm;
use Admin\Models\Users;

class AuthController extends \Phalcon\Mvc\Controller {
//	private function _registerSession($user)
//	{
//		$this->session->set('auth', array(
//			'id' 	=> $user->id,
//			'name'	=> $user->name
//		));
//	}

	public function loginAction() {
		$loginForm = new LoginForm();

		if ($this->request->isPost()) {
			if ($loginForm->isValid($_POST)) {
				//przepisuje wartości
				$nick = $this->request->getPost('nick');
				$password = $this->request->getPost('password');

				$usersModel = new Users();
				$user = $usersModel->login($nick, $password);

				echo '<pre>'; print_r($user->id); echo '</pre>';
				exit;
			} else {
				$this->flash->error($loginForm->getMessages());
			}
		}

		$this->view->setVar('loginForm', $loginForm);
//		$this->view->setVar('loginFormErrors', $errors);

		//$test = $this->getDI()->get('router');
		//$this->view->setVar('router', $test);
	}

	public function logoutAction() {
		echo 'WYLOGOWYWANIE';
	}

	private function getFormLogin() {

	}
}