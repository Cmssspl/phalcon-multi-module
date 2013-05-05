<?php

namespace Admin\Controllers;

use \Phalcon\Mvc\Controller;

class IndexController extends Controller {
	public function indexAction() {
		echo 'IndexController / indexAction';
	}

	public function testAction() {
		echo 'IndexController / testAction';
	}
}