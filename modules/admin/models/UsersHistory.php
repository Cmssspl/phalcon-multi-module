<?php

namespace Admin\Models;

class UsersHistory extends \Phalcon\Mvc\Model {
//	public $id;
//	public $users_id;
//	public $time;
//	public $ip;
//	public $browser;

	public function initialize() {
		$this->belongsTo('id', 'Users', 'users_id');
	}
}