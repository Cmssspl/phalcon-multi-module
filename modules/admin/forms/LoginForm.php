<?php

namespace Admin\Forms;

use Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Submit;



use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength;

class LoginForm extends Form {
	public function initialize() {
		//nick
		$nick = new Text('nick');

		$nick->addValidator(new PresenceOf(array(
			'message' => 'Nick jest wymagany'
		)));

		$nick->addValidator(new StringLength(array(
			'min' => 4,
			'max' => 32,
			'messageMinimum' => 'Nick jest za krótki',
			'messageMaximum' => 'Nick jest za długi'
		)));

		$this->add($nick);

		//pass
		$pass = new Text('pass');

		$pass->addValidator(new PresenceOf(array(
			'message' => 'Hasło jest wymagany'
		)));

		$pass->addValidator(new StringLength(array(
			'min' => 4,
			'max' => 32,
			'messageMinimum' => 'Hasło jest za krótki',
			'messageMaximum' => 'Hasło jest za długi'
		)));

		$this->add($pass);

		$this->add(new Submit('save'));
	}
}