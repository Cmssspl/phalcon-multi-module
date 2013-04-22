<?php

namespace Admin\Controllers;

use Admin\Forms\LoginForm;

use Admin\Models\Users,
	Admin\Models\UsersLog;

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

				if(!empty($user)) {
					$this->session->set('auth', new \Phalcon\Config(array(
						'id' => $user->id,
						'nick' => $user->nick
					)));

					$usersLogModel = new UsersLog();
					$usersLogModel->add();

					$this->flash->success('Nie prawidłowe dane');
				} else {
					$this->flash->error('Nie prawidłowe dane');
				}
			} else {
				$this->flash->error('brak danych'); //$loginForm->getMessages()
			}
		}

		$this->view->setVar('loginForm', $loginForm);
		$this->view->setVar('test', $this->session->get('auth'));

		//$test = $this->getDI()->get('router');
		//$this->view->setVar('router', $test);
	}

	public function logoutAction() {
		echo 'WYLOGOWYWANIE';
	}

	private function getFormLogin() {

	}
}