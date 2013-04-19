<?php

namespace Admin\Forms;

use Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select;

class LoginForm extends Form {
	public function initialize() {
		$this->add(new Text("name"));
		$this->add(new Text("pass"));
	}
}