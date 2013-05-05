<?php

namespace Admin\Plugins;

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Acl;

class Auth extends Plugin
{
	public function __construct($dependencyInjector) {
		$this->_dependencyInjector = $dependencyInjector;
	}

	public function getAcl() {
		$acl = new \Phalcon\Acl\Adapter\Memory();

		$acl->setDefaultAction(\Phalcon\Acl::DENY);

		//Register roles
		$roles = array(
			'guests' => new \Phalcon\Acl\Role('Guests'),
			'users' => new \Phalcon\Acl\Role('Users')
		);

		foreach ($roles as $role) {
			$acl->addRole($role);
		}

		//Private area resources
		$privateResources = array(
			'index' => array('index', 'test'),
			'auth'	=> array('logout')
		);

		foreach ($privateResources as $resource => $actions) {
			$acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
		}

		//Public area resources
		$publicResources = array(
			'auth'	=> array('login', 'registry', 'restorePassword', 'help')
		);

		foreach ($publicResources as $resource => $actions) {
			$acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
		}

		//Grant access to public areas to both users and guests
		//foreach ($roles as $role) {
			foreach ($publicResources as $resource => $actions) {
				$acl->allow('Guests', $resource, $actions);
			}
		//}

		//Grant acess to private area to role Users
		foreach ($privateResources as $resource => $actions) {
			//foreach ($actions as $action){
				$acl->allow('Users', $resource, $actions);
			//}
		}

		return $acl;
	}

	public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
		$auth = $this->session->get('auth');

		if ($auth){
			$role = 'Users';
		} else {
			$role = 'Guests';
		}

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW) {
//			if($role != 'Guests') {
//				$this->flash->error('Nie masz uprawnień');
//			}

			if($role == 'Guests') {
				if( !( $controller == 'index' && $action == 'index') ) {
					$this->flash->error('Nie masz uprawnień');
				}

				$dispatcher->forward(
					array(
						'controller' 	=> 'auth',
						'action' 		=> 'login'
					)
				);
			} else {
				$this->flash->error('Nie masz uprawnień');

				$dispatcher->forward(
					array(
						'controller' 	=> 'index',
						'action' 		=> 'index'
					)
				);
			}

			return false;
		}
	}
}
