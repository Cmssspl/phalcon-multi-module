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
//		$ini = new \Phalcon\Config\Adapter\Ini('app/configs/acl.ini');
//
//		echo '<pre>'; print_r($ini); echo '</pre>';
//		exit;

		$acl = new \Phalcon\Acl\Adapter\Memory();

		$acl->setDefaultAction(\Phalcon\Acl::DENY);

		//Register roles
		$roles = array(
			'users' => new \Phalcon\Acl\Role('Users'),
			'guests' => new \Phalcon\Acl\Role('Guests')
		);

		foreach ($roles as $role) {
			$acl->addRole($role);
		}

		//Private area resources
		$privateResources = array(
			'auth'	=> array('logout')
		);

		foreach ($privateResources as $resource => $actions) {
			$acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
		}

		//Public area resources
		$publicResources = array(
			'index' => array('index'),
			'auth'	=> array('index', 'login')
		);

		foreach ($publicResources as $resource => $actions) {
			$acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
		}

		//Grant access to public areas to both users and guests
		foreach ($roles as $role) {
			foreach ($publicResources as $resource => $actions) {
				$acl->allow($role->getName(), $resource, $actions);
			}
		}

		//Grant acess to private area to role Users
		foreach ($privateResources as $resource => $actions) {
			foreach ($actions as $action){
				$acl->allow('Users', $resource, $action);
			}
		}

		return $acl;
	}

	public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
		$auth = $this->session->get('auth');

		if (!$auth){
			$role = 'Guests';
		} else {
			$role = 'Users';
		}

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW) {
			$this->flash->error("You don't have access to this module");

			$dispatcher->forward(
				array(
					'controller' 	=> 'auth',
					'action' 		=> 'login'
				)
			);

			return false;
		}
	}
}
