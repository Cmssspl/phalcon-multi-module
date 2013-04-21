<?php

namespace Admin\Models;

class UsersLog extends \Phalcon\Mvc\Model {
//	public $id;
//	public $users_id;
//	public $time;
//	public $ip;
//	public $browser;

	public function initialize() {
		$this->belongsTo('id', 'Users', 'users_id');
	}

	public function add() {
		$request = $this->getDI()->get('request');
		$router = $this->getDI()->get('router');
		$userAuth = $this->getDI()->get('session')->get('auth');

		$this->users_id = $userAuth->id;
		$this->time = time();
		$this->ip = $request->getClientAddress();
		$this->browser = $request->getUserAgent();
		$this->controller = $router->getControllerName();
		$this->action = $router->getActionName();
		$this->params = json_encode($router->getParams());
		$this->save();
	}
}