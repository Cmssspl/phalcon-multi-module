<?php

namespace Admin\Forms;

use Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Submit;

use Phalcon\Mvc\Model\Validator\Email,
	Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength;

class RestorePasswordForm extends Form {
	public function initialize() {
		//email
		$email = new Text('email', array(
			'placeholder' => 'Email:'
		));

		$email->addValidator(new PresenceOf(array(
			'message' => 'Email jest wymagany'
		)));

		$email->addValidator(new Email(array(
			'message' => 'Email jest nie poprawny'
		)));

		$this->add($email);

		//przycisk
		$login = new Submit('restore', array(
			'value' => 'adsadsads'
		));

		$this->add($login);
	}
}