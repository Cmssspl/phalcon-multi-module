<?php

namespace Admin\Plugins;

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher;

class Template extends Plugin
{
	public function __construct($dependencyInjector) {
		$this->_dependencyInjector = $dependencyInjector;
	}

	public function afterExecuteRoute(Event $event, Dispatcher $dispatcher) {
		$auth = $this->session->get('auth');

		//layout
		if ($auth) {
			$this->view->setLayout('login');
		} else {
			$this->view->setLayout('logout');
		}

		//menu up
		if ($auth) {
			$menuTop = array(
				'logout' => 'Wyloguj'
			);
		} else {
			$menuTop = array(
				'' => 'Logowanie',
				'registry' => 'Rejestracja',
				'restorePassword' => 'Przywróć hasło',
				'help' => 'Pomoc'
			);
		}

		$this->view->setVar('menuTop', $menuTop);
	}
}
