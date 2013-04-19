<?php

namespace Admin\Models;

class Users extends \Phalcon\Mvc\Model {
	public function getAdmin() {
		return 'Load';
	}
}