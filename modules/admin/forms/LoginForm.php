<?php

namespace Admin\Forms;

use Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Submit;

class LoginForm extends Form {
	public function initialize() {
		$this->add(new Text('nick'));
		$this->add(new Text('pass'));
		$this->add(new Submit('save'));
	}
}