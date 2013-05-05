<?php

namespace Admin\Controllers;

use \Phalcon\Mvc\View;

use Admin\Forms\LoginForm,
	Admin\Forms\RegistryForm,
	Admin\Forms\RestorePasswordForm;

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

	public function initialize() {

	}

	public function loginAction() {
//
//		if ($this->request->isPost()) {
//			if ($loginForm->isValid($_POST)) {
//				//przepisuje wartości
//				$nick = $this->request->getPost('nick');
//				$password = $this->request->getPost('password');
//
//				$usersModel = new Users();
//				$user = $usersModel->login($nick, $password);
//
//				if(!empty($user)) {
//					$this->session->set('auth', new \Phalcon\Config(array(
//						'id' => $user->id,
//						'nick' => $user->nick
//					)));
//
//					$usersLogModel = new UsersLog();
//					$usersLogModel->add();
//
//					$this->flash->success('Zalogowano');
//				} else {
//					$this->flash->error('Błędny login lub hasło, spróbuj ponownie lub skorzystaj z opcji "zapomniałem hasła"');
//				}
//			} else {
//				$errors = $loginForm->getMessages();
//
//				if(!empty($errors)) {
//					foreach($errors as $error) {
//						$this->flash->error($error);
//					}
//				}
//			}
//		}
		$loginForm = new LoginForm();

		if ($this->request->isPost()) {
			if ($loginForm->isValid($_POST)) {
				$userModel = new Users();
				$loginForm->bind($_POST, $userModel);
				$user = $userModel->login();

				if($user) {
					if(!$user->isActive()) {
						$errors[] = 'Konto nie zostało aktywowane';
					}

					if($user->isBlock()) {
						$errors[] = 'Konto zostało zablokowane';
					}
				} else {
					$errors[] = 'Login lub hasło jest nieprawidłowy';
				}
			} else {
				$errors = $loginForm->getMessages();
			}

			if(!empty($errors)) {
				foreach($errors as $error) {
					$this->flash->error($error);
				}
			} else {
				//tworzy sesje
				$this->session->set('auth', new \Phalcon\Config(array(
					'id' => $user->id,
					'nick' => $user->nick
				)));

				//dodaje komunikat
				$this->flash->success('Zostałeś zalogowany');

				//przekierowanie na właściwy adres gdy user nie jest zalogowany
				return $this->dispatcher->forward(array(
					'controller' => $this->router->getControllerName(),
					'action' => $this->router->getActionName()
				));
			}
		}

		$this->view->setVar('loginForm', $loginForm);
//		$this->view->setVar('test', $this->session->get('auth'));

		//$test = $this->getDI()->get('router');
		//$this->view->setVar('router', $test);
	}

	public function restorePasswordAction() {
		$restorePasswordForm = new RestorePasswordForm();

		if ($this->request->isPost()) {
			if ($restorePasswordForm->isValid($_POST)) {
				//przepisuje wartości
				$nick = $this->request->getPost('nick');
				$password = $this->request->getPost('password');

			}
		}

		$this->view->setVar('restorePasswordForm', $restorePasswordForm);
	}

	public function registryAction() {
		$registryForm = new RegistryForm();

		if ($this->request->isPost()) {
			if ($registryForm->isValid($_POST)) {
				$user = new Users();

				$registryForm->bind($_POST, $user);

				if(!$user->checkNick()) {
					$errors[] = 'Użytkownuk o nicki '.$user->nick.' już istnieje';
				}

				if(!$user->checkEmail()) {
					$errors[] = 'Użytkownuk o emailu '.$user->email.' już istnieje';
				}

				if(empty($errors)) {
					if($user->registry()) {
						$this->flash->success('Wiadomość z linkiema aktywacyjnym została wysłana na '.$user->email);
					} else {
						$errors[] = 'Nieznany błąd, jeśli wystąpi ponownie prosimy o kontakt z administratorem';
					}
				}
			} else {
				$errors = $registryForm->getMessages();
			}

			if(!empty($errors)) {
				foreach($errors as $error) {
					$this->flash->error($error);
				}
			}
		}

		$this->view->setVar('registryForm', $registryForm);
	}

	public function helpAction() {

	}

	public function logoutAction() {
		//usuwa sesje
		$this->session->remove('auth');

		//dodaje komunikat
		$this->flash->success('Zostałeś wylogowany');

		//przekierowuje
		return $this->response->redirect('');
	}
}